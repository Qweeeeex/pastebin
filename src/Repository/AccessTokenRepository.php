<?php

namespace App\Repository;

use App\Entity\AccessToken;
use App\Security\Exceptions\TokenNotFound;
use Doctrine\Persistence\ManagerRegistry;

class AccessTokenRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccessToken::class);
    }

    public function createNew(string $token): void
    {
        $accessToken = new AccessToken();
        $accessToken->setToken($token);

        $this->saveEntity($accessToken, true);
    }

    public function getOneByValue(string $token): AccessToken
    {
        return $this->findOneBy(['token' => $token]) ?? throw new TokenNotFound();
    }
}
