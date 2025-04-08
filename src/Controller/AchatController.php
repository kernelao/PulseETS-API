<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Avatar;
use App\Entity\Theme;
use App\Service\AchatService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AchatController extends AbstractController
{
    private AchatService $achatService;

    public function __construct(AchatService $achatService)
    {
        $this->achatService = $achatService;
    }

    #[Route('/acheter-avatar/{id}', name: 'acheter_avatar')]
    public function acheterAvatar(Request $request, Avatar $avatar): Response
    {
        // Vérifier que l'utilisateur est connecté
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté pour effectuer un achat.');
        }

        $pointsRequired = 100; // Exemples de points nécessaires
        $message = $this->achatService->acheterAvatar($user, $avatar, $pointsRequired);

        // Flash message pour informer l'utilisateur
        if (strpos($message, 'Avatar acheté') !== false) {
            $this->addFlash('success', $message); // Succès de l'achat
        } else {
            $this->addFlash('error', $message); // Échec ou erreur
        }

        // Redirection vers la page de profil ou page d'achat, selon ton besoin
        return $this->redirectToRoute('profile'); // Remplace par la route que tu veux
    }

    #[Route('/acheter-theme/{id}', name: 'acheter_theme')]
    public function acheterTheme(Request $request, Theme $theme): Response
    {
        // Vérifier que l'utilisateur est connecté
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté pour effectuer un achat.');
        }

        $pointsRequired = 200; // Exemples de points nécessaires
        $message = $this->achatService->acheterTheme($user, $theme, $pointsRequired);

        // Flash message pour informer l'utilisateur
        if (strpos($message, 'Thème acheté') !== false) {
            $this->addFlash('success', $message); // Succès de l'achat
        } else {
            $this->addFlash('error', $message); // Échec ou erreur
        }

        // Redirection vers la page de profil ou page d'achat, selon ton besoin
        return $this->redirectToRoute('profile'); // Remplace par la route que tu veux
    }
}
