<?php

namespace App\Controller;

use App\Entity\Element;
use App\Entity\User;
use App\Entity\Achat; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/api/boutique')]
class BoutiqueController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    #[Route('/avatars', name: 'get_boutique_avatars', methods: ['GET'])]
    public function getAvatars(EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
    
        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'Utilisateur non connecté'], 403);
        }
    
        $avatars = $em->getRepository(Element::class)->findBy([
            'type' => 'avatar',
            'active' => true
        ]);
    
        $ownedAvatars = $user->getAchats()
            ->filter(fn($achat) => $achat->getElement()->getType() === 'avatar')
            ->map(fn($achat) => $achat->getElement()->getName())
            ->toArray();
    
        $data = array_map(fn($avatar) => [
            'id' => $avatar->getId(),
            'name' => $avatar->getName(),
            'owned' => in_array($avatar->getName(), $ownedAvatars),
        ], $avatars);
    
        return new JsonResponse($data);
    }
    

    #[Route('/themes', name: 'get_boutique_themes', methods: ['GET'])]
public function getThemes(EntityManagerInterface $em): JsonResponse
{
    $user = $this->getUser();

    if (!$user instanceof User) {
        return new JsonResponse(['message' => 'Utilisateur non connecté'], 403);
    }

    $themes = $em->getRepository(Element::class)->findBy([
        'type' => 'theme',
        'active' => true
    ]);

    $ownedThemes = $user->getAchats()
        ->filter(fn($achat) => $achat->getElement()->getType() === 'theme')
        ->map(fn($achat) => $achat->getElement()->getName())
        ->toArray();

    $data = array_map(fn($theme) => [
        'id' => $theme->getId(),
        'name' => $theme->getName(),
        'owned' => in_array($theme->getName(), $ownedThemes),
    ], $themes);

    return new JsonResponse($data);
}


    #[Route('/profile', name: 'get_boutique_profile', methods: ['GET'])]
    public function getProfile(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'Utilisateur non connecté'], 403);
        }

        $pulsePoints = $user->getTotalPulsePoints();

        $achats = $this->entityManager->getRepository(Achat::class)->findBy(['utilisateur' => $user]);

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


return new JsonResponse([
    'pulsePoints' => $user->getTotalPulsePoints(),
    'unlockedAvatars' => array_values($unlockedAvatars), // 👈 bien un tableau
    'unlockedThemes' => array_values($unlockedThemes),   // 👈 bien un tableau
]);

    }
}
