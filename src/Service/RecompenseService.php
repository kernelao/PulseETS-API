<?php

// src/Service/RecompenseService.php
namespace App\Service;

use App\Entity\Recompense;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class RecompenseService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Récupérer toutes les récompenses pour un utilisateur
    public function getUserRecompenses(User $user): array
    {
        $recompenses = $this->entityManager->getRepository(Recompense::class)->findBy(['goal' => $user->getGoals()]);

        // Optionnel : Formater les récompenses avant de les envoyer au frontend si nécessaire
        $formattedRecompenses = array_map(function (Recompense $recompense) {
            return [
                'nom' => $recompense->getNom(),
                'description' => $recompense->getDescription(),
                'avatarOffert' => $recompense->getAvatarOffert(),
            ];
        }, $recompenses);

        return $formattedRecompenses;
    }
}
