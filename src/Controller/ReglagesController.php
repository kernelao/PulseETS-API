<?php

namespace App\Controller;

use App\Entity\Reglages;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/reglages')]
final class ReglagesController extends AbstractController
{
    #[Route('/{id}', name: 'get_reglage', methods: ['GET'])]
    public function getReglage(int $id, EntityManagerInterface $em): JsonResponse
    {
        $reglage = $em->getRepository(Reglages::class)->find($id);

        if (!$reglage) {
            return $this->json(['error' => 'Réglage non trouvé'], 404);
        }

        return $this->json([
            'id' => $reglage->getId(),
            'pomodoro' => $reglage->getPomodoro(),
            'short_break' => $reglage->getShortBreak(),
            'long_break' => $reglage->getLongBreak(),
            'theme' => $reglage->getTheme(),
        ]);
    }

    #[Route('/{id}', name: 'update_reglage', methods: ['PUT'])]
    public function updateReglage(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $reglage = $em->getRepository(Reglages::class)->find($id);

        if (!$reglage) {
            return $this->json(['error' => 'Réglage non trouvé'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $reglage->setPomodoro($data['pomodoro'] ?? $reglage->getPomodoro());
        $reglage->setShortBreak($data['short_break'] ?? $reglage->getShortBreak());
        $reglage->setLongBreak($data['long_break'] ?? $reglage->getLongBreak());
        $reglage->setTheme($data['theme'] ?? $reglage->getTheme());

        $em->flush();

        return $this->json([
            'id' => $reglage->getId(),
            'pomodoro' => $reglage->getPomodoro(),
            'short_break' => $reglage->getShortBreak(),
            'long_break' => $reglage->getLongBreak(),
            'theme' => $reglage->getTheme(),
        ]);
    }
}
