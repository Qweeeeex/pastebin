<?php

namespace App\Security;

use App\Entity\User;
use App\Modules\Security\JWTToken;
use App\Repository\UserRepository;
use App\Security\Exceptions\LoginIncorrectException;
use App\Security\Exceptions\PasswordIncorrectException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Authorization
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepository $userRepository,
        private readonly TokenService $tokenService,
    ) {
    }

    /**
     * @throws LoginIncorrectException
     * @throws PasswordIncorrectException
     */
    public function login(
        string $login,
        #[\SensitiveParameter] string $password,
    ): array {
        if (!$this->userRepository->isExistsByLogin($login)) {
            throw new LoginIncorrectException();
        }

        $user = $this->userRepository->getOneByLogin($login);

        if (!$this->passwordHasher->isPasswordValid($user, $password)) {
            throw new PasswordIncorrectException();
        }

        return $this->generateNewTokenPair($user);
    }

    private function generateNewTokenPair(User $user): array
    {
        $payload = [
            'id' => $user->getId(),
            'login' => $user->getLogin(),
            'roles' => $user->getRoles(),
        ];

        $accessToken = $this->tokenService->generateNewToken($payload, JWTToken::TYPE_ACCESS);

        $this->tokenService->saveNewToken($accessToken, JWTToken::TYPE_ACCESS);

        return [
            'access_token' => $accessToken,
        ];
    }
}
