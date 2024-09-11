<?php

namespace App\Repository;

use App\DTO\Request\GetPasteListDTO;
use App\Entity\Paste;
use App\Entity\User;
use App\Repository\Exceptions\PasteNotFoundException;
use Doctrine\Persistence\ManagerRegistry;

class PasteRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Paste::class);
    }

    /**
     * @return Paste[]
     */
    public function getPublicPasteList(GetPasteListDTO $dto): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.availability = :public')
            ->andWhere('p.expirationTime >= :now')
            ->setParameter('now', new \DateTimeImmutable())
            ->setParameter('public', 'public')
            ->setFirstResult(($dto->page - 1) * $dto->limit)
            ->setMaxResults($dto->limit)
            ->getQuery()
            ->getResult();
    }

    public function getPrivatePasteList(GetPasteListDTO $dto, User $user): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.availability = :private')
            ->andWhere('p.expirationTime >= :now')
            ->andWhere('p.createdBy = :user')
            ->setParameter('user', $user)
            ->setParameter('now', new \DateTimeImmutable())
            ->setParameter('private', 'private')
            ->setFirstResult(($dto->page - 1) * $dto->limit)
            ->setMaxResults($dto->limit)
            ->getQuery()->getResult();
    }

    /**
     * @throws PasteNotFoundException
     */
    public function getPasteById(string $id, ?User $user): ?Paste
    {
        $result = $this->createQueryBuilder('p')
            ->where('p.id = :id')
            ->andWhere('p.expirationTime >= :now')
            ->andWhere('
                (p.availability = :private AND p.createdBy = :user) 
                OR (p.availability = :public)
                OR (p.availability = :unlisted)
            ')
            ->setParameter('now', new \DateTimeImmutable())
            ->setParameter('id', $id)
            ->setParameter('private', 'private')
            ->setParameter('user', $user)
            ->setParameter('public', 'public')
            ->setParameter('unlisted', 'unlisted')
            ->getQuery()->getResult();

        if (0 === count($result)) {
            throw new PasteNotFoundException();
        }

        return $result[0];
    }
}
