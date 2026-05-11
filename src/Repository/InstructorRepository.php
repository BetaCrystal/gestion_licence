<?php

namespace App\Repository;

use App\Entity\Instructor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Instructor>
 */
class InstructorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Instructor::class);
    }

    public function findAllInstructor()
{
    $qb = $this->createQueryBuilder('i')
        ->select('i.id, u.firstName, u.lastName, GROUP_CONCAT(DISTINCT m.name) AS modules, SUM(DISTINCT m.hoursCount) AS totalHours')
        ->join('i.user', 'u')
        ->leftJoin('i.Module', 'm')
        ->groupBy('i.id, u.firstName, u.lastName')
        ->orderBy('u.lastName', 'ASC');

    return $qb;
}




    public function queryForInfoInstructor(int $id)
    {
        $qb = $this->createQueryBuilder('i');

        $qb
            ->select('u.firstName AS prenom')
            ->addSelect('u.lastName AS nom')
            ->addSelect('m.name AS module')
            ->addSelect('m.hoursCount AS nbHeures')
            ->addSelect('m.hoursCount - COALESCE(SUM(TIMESTAMPDIFF(HOUR, c.startDate, c.endDate)), 0) AS nbHeuresRestantes')
            ->join('i.user', 'u')
            ->join('i.Module', 'm')
            ->leftJoin('m.courses', 'c', 'WITH', 'c.module = m')
            ->where('i.id = :id')
            ->setParameter('id', $id)
            ->groupBy('u.firstName, u.lastName, m.name, m.hoursCount');

        return $qb->getQuery()->getArrayResult();


    }

    public function queryForInfoExcel(int $id)
    {
        $qb = $this->createQueryBuilder('i');

        $qb
            ->select('u.firstName AS prenom')
            ->addSelect('u.lastName AS nom')
            ->addSelect('m.name AS module')
            ->addSelect('m.hoursCount AS nbHeures')
            ->addSelect('c.startDate AS startDate')
            ->addSelect('c.endDate AS endDate')
            ->join('i.user', 'u')
            ->join('i.Module', 'm')
            ->join('m.courses', 'c')
            ->where('i.id = :id')
            ->setParameter('id', $id);

        return $qb->getQuery()->getArrayResult();


    }
    public function queryForUserInstructor(int $id)
    {
       /* $qb = $this->createQueryBuilder('i');

        $qb
            ->select('u')
            ->join('i.user', 'u')
            ->where('u.id = :id')
            ->setParameter('id', $id);

        return $qb->getQuery()->getArrayResult();*/


    }
//    /**
//     * @return Instructor[] Returns an array of Instructor objects
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

//    public function findOneBySomeField($value): ?Instructor
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
