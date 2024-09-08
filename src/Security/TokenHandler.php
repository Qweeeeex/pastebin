<?php

namespace App\Security;

use App\Modules\Security\JWTToken;
use App\Repository\UserRepository;
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

        $employee = $this->userRepository->getOneById($tokenArray['id']);

        // and return a UserBadge object containing the user identifier from the found token
        return new UserBadge($employee->getUserIdentifier());
    }
}
