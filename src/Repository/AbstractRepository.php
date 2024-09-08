<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Collection;

abstract class AbstractRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry, string $className = '')
    {
        parent::__construct($managerRegistry, $className);
    }

    public function saveEntity(object $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function removeEntity(object $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function batchSaveEntities(array|Collection $entities): void
    {
        $em = $this->getEntityManager();
        foreach ($entities as $entity) {
            $em->persist($entity);
        }
        $em->flush();
    }

    public function batchRemoveEntities(array|Collection $entities): void
    {
        $em = $this->getEntityManager();
        foreach ($entities as $entity) {
            $em->remove($entity);
        }
        $em->flush();
    }
}
