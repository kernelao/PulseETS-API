<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Recompense;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
final class ProfileController extends AbstractController
{
    #[Route('/recompenses', name: 'profile_recompenses', methods: ['GET'])]
    public function recompenses(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'User not found'], 404);
        }

        $recompenses = $user->getRecompenses()->toArray();

        if (empty($recompenses)) {
            return new JsonResponse(['message' => 'No rewards found'], 404);
        }

        $data = array_map(fn(Recompense $r) => [
            'id' => $r->getId(),
            'name' => $r->getNom(),
            'description' => $r->getDescription(),
        ], $recompenses);

        return new JsonResponse($data);
    }

    #[Route('/points', name: 'profile_points', methods: ['GET'])]
    public function points(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'User not found'], 404);
        }

        return new JsonResponse(['points' => $user->getPulsePoints()->count()]);
    }
}