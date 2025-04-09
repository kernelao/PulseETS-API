<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Avatar;
use App\Entity\AchatAvatar;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class AvatarController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/user/avatars', name: 'get_user_avatars', methods: ['GET'])]
    public function getUserAvatars(User $user): JsonResponse
    {
        $avatars = $user->getAchatsAvatars();

        $avatarsData = [];

        foreach ($avatars as $achat) {
            if ($achat->getIsActive()) {
                $avatar = $achat->getAvatar();
                $avatarsData[] = [
                    'id' => $avatar->getId(),
                    'name' => $avatar->getName(),
                ];
            }
        }

        if (empty($avatarsData)) {
            return new JsonResponse(['message' => 'Aucun avatar actif trouvé'], 404);
        }

        return new JsonResponse($avatarsData, 200);
    }

    #[Route('/api/user/avatars', name: 'add_user_avatar', methods: ['POST'])]
    public function addUserAvatar(Request $request, UserInterface $user): JsonResponse
    {
        $avatarId = $request->request->get('avatar_id');

        if (!$avatarId) {
            return new JsonResponse(['message' => 'Avatar ID is required'], 400);
        }

        $avatar = $this->entityManager->getRepository(Avatar::class)->find($avatarId);
        if (!$avatar) {
            return new JsonResponse(['message' => 'Avatar not found'], 404);
        }

        $achat = new AchatAvatar();
        $achat->setUser($user);
        $achat->setAvatar($avatar);
        $achat->setDateAchat(new \DateTime());
        $achat->setIsActive(true);

        $this->entityManager->persist($achat);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Avatar ajouté avec succès'], 200);
    }

    #[Route('/api/user/avatars/{id}', name: 'remove_user_avatar', methods: ['DELETE'])]
    public function removeUserAvatar(int $id, UserInterface $user): JsonResponse
    {
        $achat = $this->entityManager->getRepository(AchatAvatar::class)->findOneBy([
            'user' => $user,
            'avatar' => $id,
        ]);

        if (!$achat) {
            return new JsonResponse(['message' => 'AchatAvatar non trouvé pour cet utilisateur'], 404);
        }

        $this->entityManager->remove($achat);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Avatar retiré avec succès'], 200);
    }
}
