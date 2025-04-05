<?php

// src/Controller/AdminController.php
// src/Controller/AdminController.php
namespace App\Controller;

use App\Entity\Avatar;
use App\Repository\AvatarRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

class AdminController extends AbstractController
{
    // Activer un avatar
    #[Route('/api/admin/activate-avatar/{avatarId}', name: 'admin_activate_avatar', methods: ['POST'])]
    public function activateAvatar(int $avatarId, AvatarRepository $avatarRepository, EntityManagerInterface $em): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return new JsonResponse(['message' => 'Accès interdit'], 403);
        }

        $avatar = $avatarRepository->find($avatarId);

        if (!$avatar) {
            return new JsonResponse(['message' => 'Avatar introuvable'], 404);
        }

        $avatar->setActive(true); 
        $em->flush();

        return new JsonResponse(['message' => 'Avatar activé avec succès', 'avatar' => $avatar->getActive()], 200);
    }

    // Désactiver un avatar
    #[Route('/api/admin/deactivate-avatar/{avatarId}', name: 'admin_deactivate_avatar', methods: ['POST'])]
    public function deactivateAvatar(int $avatarId, AvatarRepository $avatarRepository, EntityManagerInterface $em): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return new JsonResponse(['message' => 'Accès interdit'], 403);
        }

        $avatar = $avatarRepository->find($avatarId);

        if (!$avatar) {
            return new JsonResponse(['message' => 'Avatar introuvable'], 404);
        }

        $avatar->setActive(false); 
        $em->flush();

        return new JsonResponse(['message' => 'Avatar désactivé avec succès', 'avatar' => $avatar->getActive()], 200);
    }

    // Basculement de l'état actif d'un avatar
    #[Route('/admin/avatar/{id}/toggle', name: 'admin_toggle_avatar_status')]
    public function toggleAvatarStatus(Avatar $avatar, EntityManagerInterface $entityManager): JsonResponse
    {
        $avatar->setActive(!$avatar->getActive()); 
        $entityManager->flush();

        return new JsonResponse(['message' => 'Avatar togglé avec succès', 'avatar' => $avatar->getActive()], 200);
    }
}
