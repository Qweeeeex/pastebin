<?php

namespace App\Repository;

use App\Entity\Paste;
use App\Entity\User;
use App\Repository\Exceptions\PasteNotFoundException;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Paste>
 */
class PasteRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Paste::class);
    }

    /**
     * @return Paste[]
     */
    public function getPublicPasteList(): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.availability = :public')
            ->andWhere('p.expirationTime >= :now')
            ->setParameter('now', new DateTimeImmutable())
            ->setParameter('public', 'public')
            ->getQuery()
            ->getResult();

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
            ->setParameter('now', new DateTimeImmutable())
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
