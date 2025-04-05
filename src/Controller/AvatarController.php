<?php

// src/Controller/UserAvatarController.php
namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserAvatarController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route("/api/user/avatars", name="get_user_avatars", methods={"GET"})]
    public function getUserAvatars(UserInterface $user): JsonResponse
    {
        // Récupérer les avatars associés à l'utilisateur
        $avatars = $user->getAvatars();

        // Si aucun avatar actif n'est trouvé
        if (empty($avatars)) {
            return new JsonResponse(['message' => 'Aucun avatar trouvé'], 404);
        }

        // Filtrer les avatars actifs
        $avatarsData = [];
        foreach ($avatars as $avatar) {
            if ($avatar->getActive()) {
                $avatarsData[] = [
                    'id' => $avatar->getId(),
                    'name' => $avatar->getName(),
                    'imageUrl' => '/uploads/avatars/' . $avatar->getName() . '.jpg', // Exemple d'URL de l'image
                ];
            }
        }

        // Si aucun avatar actif n'est trouvé
        if (empty($avatarsData)) {
            return new JsonResponse(['message' => 'Aucun avatar actif trouvé'], 404);
        }

        return new JsonResponse($avatarsData, 200);
    }
}
