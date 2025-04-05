<?php

// src/Controller/BoutiqueController.php
namespace App\Controller;

use App\Entity\Avatar;
use App\Entity\Theme;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;

class BoutiqueController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/boutique/avatars', name: 'get_boutique_avatars', methods: ['GET'])]
    public function getBoutiqueAvatars(User $user): JsonResponse
    {
        // Récupérer tous les avatars
        $avatars = $this->entityManager->getRepository(Avatar::class)->findBy(['active' => true]);

        // Filtrer les avatars en fonction de si l'utilisateur les possède ou non
        $avatarsData = [];
        foreach ($avatars as $avatar) {
            $avatarsData[] = [
                'id' => $avatar->getId(),
                'name' => $avatar->getName(),
                'owned' => $user->getAvatars()->contains($avatar),
            ];
        }

        return new JsonResponse($avatarsData, 200);
    }

    #[Route('/api/boutique/themes', name: 'get_boutique_themes', methods: ['GET'])]
    public function getBoutiqueThemes(User $user): JsonResponse
    {
        // Récupérer tous les thèmes
        $themes = $this->entityManager->getRepository(Theme::class)->findBy(['active' => true]);

        // Filtrer les thèmes en fonction de si l'utilisateur les possède ou non
        $themesData = [];
        foreach ($themes as $theme) {
            $themesData[] = [
                'id' => $theme->getId(),
                'name' => $theme->getName(),
                'owned' => $user->getThemes()->contains($theme),
            ];
        }

        return new JsonResponse($themesData, 200);
    }
}
