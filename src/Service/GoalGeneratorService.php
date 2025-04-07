<?php

// src/Service/GoalGeneratorService.php
namespace App\Service;

use App\Entity\Goal;
use App\Entity\User;
use App\Entity\PulsePoint;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Ulid;

class GoalGeneratorService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function generateRandomGoal(User $user): Goal
    {
        // Liste d'objectifs possibles avec points associés
        $goals = [
            'Compléter 4 Pomodoros' => 1000,
            'Écrire 5 notes' => 500,
            'Compléter 3 tâches' => 300,
        ];

        // Choisir un objectif aléatoire
        $description = array_rand($goals);
        $points = $goals[$description];

        // Créer un nouvel objectif
        $goal = new Goal();
        $goal->setUser($user);
        $goal->setDescription($description);
        $goal->setCompleted(false);
        $goal->setDateCreated(new \DateTime());
        $goal->setPoints($points);  // Assigner des points à l'objectif

        // Persist l'objectif dans la base de données
        $this->entityManager->persist($goal);
        $this->entityManager->flush();

        return $goal;
    }

    public function completeGoal(Goal $goal): void
    {
        // Marquer l'objectif comme complété
        $goal->setCompleted(true);

        // Créer un PulsePoint pour l'utilisateur
        $user = $goal->getUser();
        $pulsePoint = new PulsePoint();
        $pulsePoint->setUser($user);
        $pulsePoint->setPoints($goal->getPoints());  // Les points de l'objectif
        $pulsePoint->setDateCreated(new \DateTime());

        // Persist PulsePoint dans la base de données
        $this->entityManager->persist($pulsePoint);
        $this->entityManager->flush();
    }
}
