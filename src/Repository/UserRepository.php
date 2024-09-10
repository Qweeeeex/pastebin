<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function isExistsByLogin(string $login): bool
    {
        return (bool) $this->findOneBy(['login' => $login]);
    }

    public function getOneByLogin(string $identifier): User
    {
        return $this->findOneBy(['login' => $identifier]);
    }

    public function getOneById(mixed $id): User
    {
        return $this->find($id);
    }
}
