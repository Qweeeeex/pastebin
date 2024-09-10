<?php

namespace App\Security;

use App\Entity\AccessToken;
use App\Modules\Security\Exceptions\UnknownTokenType;
use App\Modules\Security\JWTToken;
use App\Repository\AccessTokenRepository;
use App\Security\Exceptions\ExpiredTokenException;
use App\Security\Exceptions\NotValidTokenException;

class TokenService extends JWTToken
{
    public function __construct(
        private AccessTokenRepository $accessTokenRepository,
    ) {
    }

    public function decodeAndCheck(string $fullToken, string $type): array
    {
        $encodedTokenArray = explode('.', $fullToken);

        if (3 !== count($encodedTokenArray)) {
            throw new NotValidTokenException();
        }

        $encodedHead = $encodedTokenArray[0];
        $encodedPayload = $encodedTokenArray[1];
        $encodedServerSignature = $encodedTokenArray[2];

        if (!$this->verifyBySignature($encodedHead, $encodedPayload, $encodedServerSignature)) {
            throw new NotValidTokenException();
        }

        $tokenArray = $this->decodeToken($encodedHead, $encodedPayload);

        if ($this->isExpired($tokenArray)) {
            throw new ExpiredTokenException();
        }

        if (!$this->validEmployeeServiceId($tokenArray)) {
            throw new NotValidTokenException();
        }

        $dbToken = $this->getTokenFromReposiotry($fullToken, $type);
        if (!isset($dbToken)) {
            throw new NotValidTokenException();
        }

        return $tokenArray;
    }

    private function isExpired(array $tokenArray): bool
    {
        return $tokenArray['exp'] < time();
    }

    private function validEmployeeServiceId(array $tokenArray): bool
    {
        return isset($tokenArray['id']);
    }

    private function verifyBySignature(string $encodedHead, string $encodedPayload, string $encodedServerSignature): bool
    {
        $signature = $this->decodeSignature($encodedServerSignature);

        if (false === $signature || 2 !== count($signature)) {
            return false;
        }

        // Проверяем
        if ($signature[0] !== $encodedHead || $signature[1] !== $encodedPayload) {
            return false;
        }

        return true;
    }

    private function getTokenFromReposiotry(string $token, string $type): ?AccessToken
    {
        return match ($type) {
            JWTToken::TYPE_ACCESS => $this->accessTokenRepository->getOneByValue($token),
            default => throw new UnknownTokenType(),
        };
    }

    public function generateNewToken(array $payload, string $type): string
    {
        return $this->generateToken($type, $payload);
    }

    public function saveNewToken(string $token, string $type): void
    {
        $this->accessTokenRepository->createNew($token);
    }
}
