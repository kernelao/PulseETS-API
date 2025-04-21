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

    #[Route('/me', name: 'get_or_create_my_reglage', methods: ['GET'])]
    public function getOrCreateMyReglage(EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'Non authentifié'], 401);
        }

        $reglage = $em->getRepository(Reglages::class)->findOneBy(['userNb' => $user]);

        if (!$reglage) {
            // Aller chercher le default réglage défini par l'admin
            $default = $em->getRepository(\App\Entity\DefaultReglage::class)->findOneBy([], ['id' => 'DESC']);
        
            $reglage = new Reglages();
            $reglage->setUserNb($user);
            $reglage->setPomodoro($default?->getPomodoro() ?? 25);
            $reglage->setCourtePause($default?->getCourtePause() ?? 5);
            $reglage->setLonguePause($default?->getLonguePause() ?? 15);
            $reglage->setTheme($default?->getTheme() ?? 'Mode zen');
        
            $em->persist($reglage);
            $em->flush();
        }
        

        return $this->json([
            'id' => $reglage->getId(),
            'pomodoro' => $reglage->getPomodoro(),
            'courte_pause' => $reglage->getCourtePause(),
            'longue_pause' => $reglage->getLonguePause(),
            'theme' => $reglage->getTheme(),
        ]);
    }

    
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
            'courte_pause' => $reglage->getCourtePause(),
            'longue_pause' => $reglage->getLonguePause(),
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
        $reglage->setCourtePause($data['courte_pause'] ?? $reglage->getCourtePause());
        $reglage->setLonguePause($data['longue_pause'] ?? $reglage->getLonguePause());
        $reglage->setTheme($data['theme'] ?? $reglage->getTheme());

        $em->flush();

        return $this->json([
            'id' => $reglage->getId(),
            'pomodoro' => $reglage->getPomodoro(),
            'courte_pause' => $reglage->getCourtePause(),
            'longue_pause' => $reglage->getLonguePause(),
            'theme' => $reglage->getTheme(),
        ]);
    }
}
