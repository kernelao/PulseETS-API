<?php

namespace App\Repository;

use App\Entity\Avatar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Avatar|null find($id, $lockMode = null, $lockVersion = null)
 * @method Avatar|null findOneBy(array $criteria, array $orderBy = null)
 * @method Avatar[]    findAll()
 * @method Avatar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvatarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avatar::class);
    }

    // Méthode pour récupérer les avatars en fonction des critères
    public function findAvailableAvatars()
    {
        $currentDate = new \DateTime();
        return $this->createQueryBuilder('a')
            ->where('a.availableFrom IS NULL OR a.availableFrom <= :currentDate')
            ->andWhere('a.availableUntil IS NULL OR a.availableUntil >= :currentDate')
            ->andWhere('a.isEventExclusive = :isEventExclusive OR a.isEventExclusive = 0')
            ->setParameter('currentDate', $currentDate)
            ->setParameter('isEventExclusive', false)
            ->getQuery()
            ->getResult();
    }
}