<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

#[Route('/api')]
final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'api_profile', methods: ['GET'])]
    public function profile(Security $security): JsonResponse
    {
        $user = $security->getUser();

        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], 404);
        }

        return new JsonResponse([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
        ]);
    }


    #[Route('/edit', name: 'profile_edit', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function editProfile(Request $request, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();

        if (!$user instanceof User) {
            return new Response('Utilisateur non valide', 403);
        }

        $oldEmail = $request->request->get('oldEmail');
        $newEmail = $request->request->get('newEmail');

        if ($oldEmail !== $user->getEmail()) {
            return new Response('L\'ancien email est incorrect', 401);
        }

        // Modifier l'email si fourni
        if ($newEmail) {
            $user->setEmail($newEmail);
        }

        // Sauvegarde en base de données
        $entityManager->persist($user);
        $entityManager->flush();

        return new Response('Profil mis à jour avec succès');
    }

    #[Route('/change-password', name: 'change_password', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function changePassword(Request $request, Security $security, UserPasswordEncoderInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
    $user = $security->getUser();

    if (!$user instanceof User) {
        return new Response('Utilisateur non valide', 403);
    }

    $oldPassword = $request->request->get('oldPsw');
    $newPassword = $request->request->get('newPsw');

    // Vérification du mot de passe actuel
    if (!$passwordHasher->isPasswordValid($user, $oldPassword)) {
        return new Response('Ancien mot de passe incorrect', 401);
    }

    // Encoder le nouveau mot de passe
    $encodedPassword = $passwordHasher->hash($newPassword);
    $user->setPassword($encodedPassword);

    // Sauvegarde en base de données
    $entityManager->persist($user);
    $entityManager->flush();

    return new Response('Mot de passe modifié avec succès');
}


}
