<?php

namespace App\Security;

use App\Modules\Security\JWTToken;
use App\Repository\Exceptions\UserNotFoundException;
use App\Repository\UserRepository;
use App\Security\Exceptions\TokenNotFound;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class TokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private TokenService $tokenService,
        private UserRepository $userRepository,
    ) {
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $tokenArray = $this->tokenService->decodeAndCheck($accessToken, JWTToken::TYPE_ACCESS);

        try {
            $employee = $this->userRepository->getOneById($tokenArray['id']);
        } catch (UserNotFoundException) {
            throw new TokenNotFound();
        }

        return new UserBadge($employee->getUserIdentifier());
    }
}
