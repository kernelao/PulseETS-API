<?php

namespace App\Controller;

use App\Entity\Goal;
use App\Service\GoalGeneratorService;
use App\Service\RecompenseService;
use Doctrine\ORM\EntityManagerInterface; // Ajoute cette ligne pour l'EntityManager
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class GoalController extends AbstractController
{
    private $goalGeneratorService;
    private $recompenseService;
    private $entityManager; // Ajoute une propriété pour l'EntityManager

    // Injecte l'EntityManagerInterface dans le constructeur
    public function __construct(GoalGeneratorService $goalGeneratorService, RecompenseService $recompenseService, EntityManagerInterface $entityManager)
    {
        $this->goalGeneratorService = $goalGeneratorService;
        $this->recompenseService = $recompenseService;
        $this->entityManager = $entityManager; // Assigne l'EntityManager
    }

    #[Route('/api/user/goals/random', name: 'generate_random_goal', methods: ['POST'])]
    public function generateRandomGoal(UserInterface $user): JsonResponse
    {
        // Générer un objectif aléatoire
        $goal = $this->goalGeneratorService->generateRandomGoal($user);

        return new JsonResponse([
            'message' => 'Un objectif aléatoire a été généré.',
            'goal' => [
                'description' => $goal->getDescription(),
                'points' => $goal->getPoints(),
                'dateCreated' => $goal->getDateCreated()->format('Y-m-d H:i:s'),
            ]
        ], 200);
    }

    #[Route('/api/user/goals/{goalId}/complete', name: 'complete_goal', methods: ['POST'])]
    public function completeGoal(int $goalId, UserInterface $user): JsonResponse
    {
        // Utiliser l'EntityManager pour récupérer l'objectif
        $goal = $this->entityManager->getRepository(Goal::class)->find($goalId);

        if (!$goal) {
            return new JsonResponse(['message' => 'Objectif non trouvé'], 404);
        }

        // Marquer l'objectif comme complété et attribuer des points
        $this->goalGeneratorService->completeGoal($goal);

        // Récupérer les récompenses associées à l'utilisateur après la complétion
        $recompenses = $this->recompenseService->getUserRecompenses($user);

        return new JsonResponse([
            'message' => 'Objectif complété et PulsePoint attribué',
            'recompenses' => $recompenses,  // Inclure les récompenses dans la réponse
        ], 200);
    }
}
