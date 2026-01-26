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

    public function findAllInstructor(): array
{
    return $this->createQueryBuilder('i')
        ->select('u.firstName, u.lastName, m.name, m.hours_count')
        ->join('i.user_id', 'u') // param 1 = jointure de instructor vers user, param 2 = création d'un alias pour user 'u'
        ->join('i.modules', 'm') //param 1 = jointure de instructor vers module, param 2 = création d'un alias pour module 'm'
        ->getQuery()
        ->getResult();
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
