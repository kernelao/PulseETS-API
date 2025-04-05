<?php

namespace App\Repository;

use App\Entity\AchatAvatar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AchatAvatarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AchatAvatar::class);
    }

    // Exemple de méthode pour obtenir tous les achats d'avatars pour un utilisateur
    public function findByUser($user)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    // Exemple de méthode pour récupérer un achat d'avatar spécifique
    public function findOneByUserAndAvatar($user, $avatarId)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.user = :user')
            ->andWhere('a.avatar = :avatar')
            ->setParameter('user', $user)
            ->setParameter('avatar', $avatarId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
