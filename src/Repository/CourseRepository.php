<?php

namespace App\Repository;

use App\Entity\Course;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Course>
 */
class CourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Course::class);
    }

    public function queryForList()
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->select(
                'c.id',
                'c.startDate',
                'c.endDate',
                'c.remotely',
                'it.name AS interventionType',
                'm.name AS moduleName',
                "GROUP_CONCAT(CONCAT(SUBSTRING(u.firstName,1,1), '. ', u.lastName) SEPARATOR ', ') AS intervenants"
            )
            ->innerJoin('c.interventionType', 'it', 'c.intervention_type_id = it.id')
            ->innerJoin('c.module', 'm', 'c.module_id = m.id')
            ->innerJoin('c.CourseInstructor', 'ci', 'ci.course_id = c.id')
            ->innerJoin('ci.user', 'u', 'ci.user = u.id')
            ->groupBy('c.id,c.startDate,c.endDate,c.remotely,it.name,m.name');

            return $qb;
    }

    //    /**
    //     * @return Course[] Returns an array of Course objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Course
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
