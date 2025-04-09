<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Avatar;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;

class AvatarController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/user/avatars', name: 'get_user_avatars', methods: ['GET'])]
    public function getUserAvatars(User $user): JsonResponse
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
                ];
            }
        }

        // Si aucun avatar actif n'est trouvé
        if (empty($avatarsData)) {
            return new JsonResponse(['message' => 'Aucun avatar actif trouvé'], 404);
        }

        return new JsonResponse($avatarsData, 200);
    }

    #[Route('/api/user/avatars', name: 'add_user_avatar', methods: ['POST'])]
    public function addUserAvatar(User $user, Request $request): JsonResponse
    {
        $avatarId = $request->request->get('avatar_id');
        if (!$avatarId) {
            return new JsonResponse(['message' => 'Avatar ID is required'], 400);
        }

        // Récupérer l'avatar en fonction de l'ID
        $avatar = $this->entityManager->getRepository(Avatar::class)->find($avatarId);
        if (!$avatar) {
            return new JsonResponse(['message' => 'Avatar not found'], 404);
        }

        // Ajouter l'avatar à l'utilisateur
        $user->addAvatar($avatar);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Avatar added successfully'], 200);
    }

    #[Route('/api/user/avatars/{avatarId}', name: 'remove_user_avatar', methods: ['DELETE'])]
    public function removeUserAvatar(int $id, User $user): JsonResponse
    {
        // Récupérer l'avatar en fonction de l'ID
        $avatar = $this->entityManager->getRepository(Avatar::class)->find($id);
        if (!$avatar) {
            return new JsonResponse(['message' => 'Avatar not found'], 404);
        }

        // Vérifier si l'avatar est associé à l'utilisateur
        if (!$user->getAvatars()->contains($avatar)) {
            return new JsonResponse(['message' => 'Avatar is not associated with this user'], 400);
        }

        // Supprimer l'avatar de l'utilisateur
        $user->removeAvatar($avatar);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Avatar removed successfully'], 200);
    }

}