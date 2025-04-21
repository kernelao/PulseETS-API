<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Element;
use App\Entity\User;
use App\Repository\AchatRepository;
use App\Repository\ElementRepository;
use App\Repository\RecompenseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/profile')]
#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private AchatRepository $achatRepository;
    private ElementRepository $elementRepository;

    public function __construct(EntityManagerInterface $entityManager, AchatRepository $achatRepository, ElementRepository $elementRepository)
    {
        $this->entityManager = $entityManager;
        $this->achatRepository = $achatRepository;
        $this->elementRepository = $elementRepository;
    }

    #[Route('', name: 'get_profile', methods: ['GET'])]
public function getProfile(RecompenseRepository $recompenseRepository): JsonResponse
{
    $user = $this->getUser();

    if (!$user instanceof User) {
        return new JsonResponse(['message' => 'Utilisateur non connecté'], 403);
    }

    // Points totaux
    $points = $user->getTotalPulsePoints();

    // Avatars et thèmes débloqués
    $achats = $this->achatRepository->findBy(['utilisateur' => $user]);

    $unlockedAvatars = [];
    $unlockedThemes = [];

    foreach ($achats as $achat) {
        $element = $achat->getElement();
        if ($element->getType() === 'avatar') {
            $unlockedAvatars[] = $element->getName();
        } elseif ($element->getType() === 'theme') {
            $unlockedThemes[] = $element->getName();
        }
    }

    // Récompenses débloquées
    $recompenses = $recompenseRepository->findBy(['utilisateur' => $user]);
    $recompensesData = array_map(function ($r) {
        return [
            'type' => $r->getType(),
            'valeur' => $r->getValeur(),
            'nom' => $r->getNom(),
            'description' => $r->getDescription(),
            'avatarOffert' => $r->getAvatarOffert(),
            'dateDebloquee' => $r->getDateDebloquee()?->format('Y-m-d H:i:s'),
        ];
    }, $recompenses);

    return new JsonResponse([
        'email' => $user->getEmail(),
        'points' => $points,
        'pulsePoints' => $points,
        'unlockedAvatars' => $unlockedAvatars,
        'unlockedThemes' => $unlockedThemes,
        'avatarPrincipal' => $user->getAvatarPrincipal()?->getName(),
        'recompenses' => $recompensesData
    ]);
}

}
