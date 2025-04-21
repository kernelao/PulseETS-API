<?php

namespace App\Controller;

use App\Entity\DefaultReglage;
use App\Repository\DefaultReglageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/admin/reglages')]
class DefaultReglageController extends AbstractController
{
    #[Route('', name: 'admin_get_default_reglage', methods: ['GET'])]
    public function getDefault(DefaultReglageRepository $repo): JsonResponse
    {
        $reglage = $repo->findOneBy([],['id' => 'DESC']); // On prend le premier (et seul) en base

        if (!$reglage) {
            return $this->json([
                'pomodoro' => 25,
                'courte_pause' => 5,
                'longue_pause' => 15,
                'theme' => 'Mode zen',
            ]);
        }

        return $this->json([
            'id' => $reglage->getId(),
            'pomodoro' => $reglage->getPomodoro(),
            'courte_pause' => $reglage->getCourtePause(),
            'longue_pause' => $reglage->getLonguePause(),
            'theme' => $reglage->getTheme(),
        ]);
    }

    #[Route('', name: 'admin_update_default_reglage', methods: ['PUT'])]
    public function updateDefault(Request $request, EntityManagerInterface $em, DefaultReglageRepository $repo): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    
        $reglage = $repo->findOneBy([],['id' => 'DESC']); // toujours le premier (ou aucun)
    
        if (!$reglage) {
            $reglage = new DefaultReglage();
        }
    
        $reglage->setPomodoro($data['pomodoro'] ?? $reglage->getPomodoro());
        $reglage->setCourtePause($data['courte_pause'] ?? $reglage->getCourtePause());
        $reglage->setLonguePause($data['longue_pause'] ?? $reglage->getLonguePause());
        $reglage->setTheme($data['theme'] ?? $reglage->getTheme());
    
        $em->persist($reglage); // au cas où c'est un nouveau
        $em->flush();
    
        return $this->json(['message' => 'Réglages par défaut mis à jour']);
    }
    
}
