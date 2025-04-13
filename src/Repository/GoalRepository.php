<?php

namespace App\Repository;

use App\Entity\Goal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GoalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Goal::class);
    }

    // Exemple de méthode pour obtenir tous les objectifs d'un utilisateur
    public function findByUser($user)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    // Exemple de méthode pour récupérer un objectif spécifique
    public function findOneByUserAndGoalId($user, $goalId)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.user = :user')
            ->andWhere('g.id = :goalId')
            ->setParameter('user', $user)
            ->setParameter('goalId', $goalId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // Exemple de méthode pour obtenir les objectifs non complétés d'un utilisateur
    public function findIncompleteGoals($user)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.user = :user')
            ->andWhere('g.completed = :completed')
            ->setParameter('user', $user)
            ->setParameter('completed', false)
            ->getQuery()
            ->getResult();
    }
}
