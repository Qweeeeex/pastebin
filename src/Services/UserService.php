<?php

namespace App\Services;

use App\DTO\Request\PostUserDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Service\Attribute\Required;

class UserService
{
    private readonly UserRepository $userRepository;
    private readonly UserPasswordHasherInterface $passwordHasher;

    #[Required]
    public function setDependencies(
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
    ): void {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function createUser(PostUserDTO $dto): User
    {
        $user = new User();
        $user->setLogin($dto->login);
        $user->setPassword($this->passwordHasher->hashPassword($user, $dto->password));
        $this->userRepository->saveEntity($user);

        return $user;
    }
}
