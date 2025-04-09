<?php

namespace App\Repository;

use App\Entity\PulsePoint;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PulsePoint>
 */
class PulsePointRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PulsePoint::class);
    }

    public function getTotalPointsForUser(User $user): int
{
    $qb = $this->createQueryBuilder('p')
        ->select('SUM(p.points)')
        ->where('p.utilisateur = :user')
        ->setParameter('user', $user);

    return (int) $qb->getQuery()->getSingleScalarResult();
}


    //    /**
    //     * @return PulsePoint[] Returns an array of PulsePoint objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?PulsePoint
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
