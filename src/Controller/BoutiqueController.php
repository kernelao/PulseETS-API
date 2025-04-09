<?php

namespace App\Controller;

use App\Entity\Avatar;
use App\Entity\Theme;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/api/boutique')]
class BoutiqueController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/avatars', name: 'get_boutique_avatars', methods: ['GET'])]
    public function getBoutiqueAvatars(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'Utilisateur non connecté'], 403);
        }

        $avatars = $this->entityManager->getRepository(Avatar::class)->findBy(['active' => true]);
        $ownedIds = $user->getAchatsAvatars()->map(fn($achat) => $achat->getAvatar()->getId())->toArray();

        $avatarsData = array_map(function (Avatar $avatar) use ($ownedIds) {
            return [
                'id' => $avatar->getId(),
                'name' => $avatar->getName(),
                'owned' => in_array($avatar->getId(), $ownedIds),
            ];
        }, $avatars);

        return new JsonResponse($avatarsData, 200);
    }

    #[Route('/themes', name: 'get_boutique_themes', methods: ['GET'])]
    public function getBoutiqueThemes(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'Utilisateur non connecté'], 403);
        }

        $themes = $this->entityManager->getRepository(Theme::class)->findBy(['active' => true]);
        $ownedNames = $user->getAchatThemes()->map(fn($achat) => $achat->getTheme()->getName())->toArray();

        $themesData = array_map(function (Theme $theme) use ($ownedNames) {
            return [
                'id' => $theme->getId(),
                'name' => $theme->getName(),
                'owned' => in_array($theme->getName(), $ownedNames),
            ];
        }, $themes);

        return new JsonResponse($themesData, 200);
    }

    #[Route('/profile', name: 'get_boutique_profile', methods: ['GET'])]
    public function getProfile(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'Utilisateur non connecté'], 403);
        }

        $pulsePoints = $user->getTotalPulsePoints();

        $unlockedAvatars = $user->getAchatsAvatars()->map(
            fn($achat) => $achat->getAvatar()->getName()
        )->toArray();

        $unlockedThemes = $user->getAchatThemes()->map(
            fn($achat) => $achat->getTheme()->getName()
        )->toArray();

        $avatarPrincipal = $user->getAvatarPrincipal()?->getAvatar()?->getName();

        return new JsonResponse([
            'pulsePoints' => $pulsePoints,
            'unlockedAvatars' => $unlockedAvatars,
            'unlockedThemes' => $unlockedThemes,
            'avatarPrincipal' => $avatarPrincipal,
        ]);
    }
}
