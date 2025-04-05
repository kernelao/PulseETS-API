<?php

namespace App\Controller;

use App\Entity\Theme;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


final class ThemeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/admin/themes/{id}/toggle', name: 'admin_toggle_theme', methods: ['POST'])]
    public function toggleTheme(int $id, AuthorizationCheckerInterface $authChecker): JsonResponse
    {
        // Vérifier si l'utilisateur est un administrateur
        if (!$authChecker->isGranted('ROLE_ADMIN')) {
            return new JsonResponse(['message' => 'Access denied'], 403);
        }

        // Récupérer le thème en fonction de l'ID
        $theme = $this->entityManager->getRepository(Theme::class)->find($id);
        if (!$theme) {
            return new JsonResponse(['message' => 'Theme not found'], 404);
        }

        // Inverser l'état du thème (actif ou non)
        $theme->setActive(!$theme->isActive());
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Theme status updated successfully', 'active' => $theme->isActive()], 200);
    }
}
