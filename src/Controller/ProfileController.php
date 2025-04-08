<?php

namespace App\Controller;

use App\Entity\Avatar;
use App\Entity\Theme;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'api_profile', methods: ['GET'])]
    public function profile(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'User not found'], 404);
        }

        $avatar = $user->getAvatars()->first();

        $avatarsPossedes = [];
        foreach ($user->getAvatars() as $a) {
            $avatarsPossedes[] = [
                'id' => $a->getId(),
                'image' => $a->getUrl()
            ];
        }

        $recompenses = array_map(fn($r) => [
            'id' => $r->getId(),
            'name' => $r->getName(),
            'description' => $r->getDescription()
        ], $user->getRecompenses()->toArray());

        $points = $user->getPulsePoints()->count();

        return new JsonResponse([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'avatar' => $avatar ? $avatar->getUrl() : null,
            'avatarsPossedes' => $avatarsPossedes,
            'recompenses' => $recompenses,
            'points' => $points,
        ]);
    }

    #[Route('/profile/edit', name: 'profile_edit', methods: ['POST'])]
    public function editProfile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new Response('Utilisateur non valide', 403);
        }

        $oldEmail = $request->request->get('oldEmail');
        $newEmail = $request->request->get('newEmail');

        if ($oldEmail !== $user->getEmail()) {
            return new Response('L\'ancien email est incorrect', 401);
        }

        if ($newEmail) {
            $user->setEmail($newEmail);
        }

        $entityManager->flush();

        return new Response('Profil mis à jour avec succès');
    }

    #[Route('/profile/change-password', name: 'change_password', methods: ['POST'])]
    public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new Response('Utilisateur non valide', 403);
        }

        $oldPassword = $request->request->get('oldPsw');
        $newPassword = $request->request->get('newPsw');

        if (!$passwordHasher->isPasswordValid($user, $oldPassword)) {
            return new Response('Ancien mot de passe incorrect', 401);
        }

        $encodedPassword = $passwordHasher->hashPassword($user, $newPassword);
        $user->setPassword($encodedPassword);

        $entityManager->flush();

        return new Response('Mot de passe modifié avec succès');
    }

    #[Route('/profile/update-avatar', name: 'update_avatar', methods: ['POST'])]
    public function updateAvatar(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'Utilisateur non valide'], 403);
        }

        $avatarId = $request->request->get('avatarId');
        $avatar = $entityManager->getRepository(Avatar::class)->find($avatarId);

        if (!$avatar) {
            return new JsonResponse(['message' => 'Avatar non trouvé'], 404);
        }

        if (!$user->getAvatars()->contains($avatar)) {
            $user->addAvatar($avatar);
            $entityManager->flush();
        }

        return new JsonResponse(['message' => 'Avatar mis à jour avec succès']);
    }

    #[Route('/themes', name: 'profile_themes', methods: ['GET'])]
    public function themes(EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser(); 

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'User not found'], 404);
        }

        $themes = $entityManager->getRepository(Theme::class)->findBy(['active' => true]);

        $themeData = array_map(fn($theme) => [
            'id' => $theme->getId(),
            'name' => $theme->getName(),
            'price' => $theme->getPrice(),
        ], $themes);

        return new JsonResponse($themeData);
    }

    #[Route('/recompenses', name: 'profile_recompenses', methods: ['GET'])]
    public function recompenses(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'User not found'], 404);
        }

        $recompenses = $user->getRecompenses()->toArray(); 

        if (empty($recompenses)) {
            return new JsonResponse(['message' => 'No rewards found'], 404);
        }

        $data = array_map(fn($recompense) => [
            'id' => $recompense->getId(),
            'name' => $recompense->getName(),
            'description' => $recompense->getDescription(),
        ], $recompenses);

        return new JsonResponse($data);
    }

    #[Route('/points', name: 'profile_points', methods: ['GET'])]
    public function points(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'User not found'], 404);
        }

        return new JsonResponse(['points' => $user->getPulsePoints()->count()]);
    }

    #[Route('/boutique/profile', name: 'get_boutique_profile', methods: ['GET'])]
    public function getProfile(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'User not found'], 404);
        }

        $pulsePoints = $user->getPulsePoints()->count();
        $unlockedAvatars = $user->getAvatars()->map(fn($avatar) => $avatar->getId());
        $unlockedThemes = $user->getThemes()->map(fn($theme) => $theme->getId());

        return new JsonResponse([
            'pulsePoints' => $pulsePoints,
            'unlockedAvatars' => $unlockedAvatars->toArray(),
            'unlockedThemes' => $unlockedThemes->toArray(),
        ], 200);
    }
}
