<?php

namespace App\Repository;

use App\Entity\Indisponible;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Instructor;

/**
 * @extends ServiceEntityRepository<Indisponible>
 */
class IndisponibleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Indisponible::class);
    }

    public function queryForIndisponible(Instructor $instructor)
    {
        $qb = $this->createQueryBuilder("i");

        $qb->where('i.instructor = id')
            ->setParameter('id', $instructor->getId());
            return $qb->getQuery()->execute();
    }

    //    /**
    //     * @return Indisponible[] Returns an array of Indisponible objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Indisponible
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
