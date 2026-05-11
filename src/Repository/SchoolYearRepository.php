<?php

namespace App\Repository;

use App\Entity\SchoolYear;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SchoolYear>
 */
class SchoolYearRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SchoolYear::class);
    }

    //    /**
    //     * @return SchoolYear[] Returns an array of SchoolYear objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    public function findCurrent(): ?SchoolYear
    {
        $now = new \DateTime();
        return $this->createQueryBuilder('s')
            ->andWhere('s.startDate <= :now')
            ->andWhere('s.endDate >= :now')
            ->orderBy('s.startDate', 'DESC')
            ->setMaxResults(1)
            ->setParameter('now', $now)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
