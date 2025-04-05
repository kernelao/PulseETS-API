<?php

// src/Controller/AdminController.php
namespace App\Controller;

use App\Entity\Avatar;
use App\Entity\Theme;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class AdminController extends AbstractController
{
    private $entityManager;

    // Injection de l'EntityManager via le constructeur
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin/themes', name: 'admin_themes')]
    public function manageThemes(): Response
    {
        // Récupérer tous les thèmes via le repository de Doctrine
        $themes = $this->entityManager->getRepository(Theme::class)->findAll();

        return $this->render('admin/manage_themes.html.twig', [
            'themes' => $themes,
        ]);
    }

    #[Route('/admin/avatars', name: 'admin_avatars')]
    public function manageAvatars(): Response
    {
        // Récupérer tous les avatars via le repository de Doctrine
        $avatars = $this->entityManager->getRepository(Avatar::class)->findAll();

        return $this->render('admin/manage_avatars.html.twig', [
            'avatars' => $avatars,
        ]);
    }

    // Route pour activer ou désactiver un thème
    #[Route('/admin/themes/{id}/toggle', name: 'admin_toggle_theme', methods: ['POST'])]
    public function toggleTheme(int $id): RedirectResponse
    {
        $theme = $this->entityManager->getRepository(Theme::class)->find($id);

        if (!$theme) {
            $this->addFlash('error', 'Le thème n\'a pas été trouvé.');
            return $this->redirectToRoute('admin_themes');
        }

        // Inverser l'état du thème
        $theme->setActive(!$theme->isActive());
        $this->entityManager->flush();

        $this->addFlash('success', 'Le thème a été mis à jour.');
        return $this->redirectToRoute('admin_themes');
    }

    // Route pour activer ou désactiver un avatar
    #[Route('/admin/avatars/{id}/toggle', name: 'admin_toggle_avatar', methods: ['POST'])]
    public function toggleAvatar(int $id): RedirectResponse
    {
        $avatar = $this->entityManager->getRepository(Avatar::class)->find($id);

        if (!$avatar) {
            $this->addFlash('error', 'L\'avatar n\'a pas été trouvé.');
            return $this->redirectToRoute('admin_avatars');
        }

        // Inverser l'état de l'avatar
        $avatar->setActive(!$avatar->isActive());
        $this->entityManager->flush();

        $this->addFlash('success', 'L\'avatar a été mis à jour.');
        return $this->redirectToRoute('admin_avatars');
    }
}
