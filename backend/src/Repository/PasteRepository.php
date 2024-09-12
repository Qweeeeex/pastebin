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
     * @return array{items: Paste[], count: int}
     */
    public function getPublicPasteList(GetPasteListDTO $dto): array
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.availability = :public')
            ->andWhere('p.expirationTime >= :now')
            ->setParameter('now', new \DateTimeImmutable())
            ->setParameter('public', 'public');

        $countQuery = clone $query;
        $countQuery->select('COUNT(p.id)');
        $count = $countQuery->getQuery()->getSingleScalarResult();

        $result = $query
            ->setFirstResult(($dto->page - 1) * $dto->limit)
            ->setMaxResults($dto->limit)
            ->getQuery()->getResult();
        return [
            'items' => $result,
            'count' => $count,
        ];
    }

    /**
     * @return array{items: Paste[], count: int}
     */
    public function getPrivatePasteList(GetPasteListDTO $dto, User $user): array
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.availability = :private AND p.createdBy = :user')
            ->orWhere('p.availability = :unlisted AND p.createdBy = :user')
            ->andWhere('p.expirationTime >= :now')
            ->setParameter('user', $user)
            ->setParameter('unlisted', 'unlisted')
            ->setParameter('now', new \DateTimeImmutable())
            ->setParameter('private', 'private');

        $countQuery = clone $query;
        $countQuery->select('COUNT(p.id)');
        $count = $countQuery->getQuery()->getSingleScalarResult();

        $result = $query
            ->setFirstResult(($dto->page - 1) * $dto->limit)
            ->setMaxResults($dto->limit)
            ->getQuery()->getResult();
        return [
            'items' => $result,
            'count' => $count,
        ];
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
