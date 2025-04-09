<?php

namespace App\Controller;

use App\Entity\Avatar;
use App\Entity\Theme;
use App\Entity\User;
use App\Entity\AchatTheme;
use App\Entity\PulsePoint;
use App\Entity\AchatAvatar;
use App\Repository\UserRepository;
use App\Repository\GoalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
final class UserController extends AbstractController
{
    #[Route('/profile', name: 'user_profile', methods: ['GET'])]
    public function getProfile(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'Utilisateur non connecté'], 401);
        }

        $avatarsPossedes = array_map(fn($achat) => [
            'id' => $achat->getAvatar()->getId(),
            'image' => $achat->getAvatar()->getName(),
        ], $user->getAchatsAvatars()->toArray());

        $themesPossedes = array_map(fn($achat) => [
            'id' => $achat->getTheme()->getId(),
            'name' => $achat->getTheme()->getName(),
        ], $user->getAchatThemes()->toArray());

        $recompenses = array_map(fn($r) => [
            'id' => $r->getId(),
            'name' => $r->getName(),
            'description' => $r->getDescription(),
        ], $user->getRecompenses()->toArray());

        $pulsePoints = $user->getTotalPulsePoints();

        return new JsonResponse([
            'id' => $user->getId(),
            'username' => $user->getUserIdentifier(),
            'avatar' => $user->getAvatarPrincipal()?->getAvatar()?->getName(),
            'avatarsPossedes' => $avatarsPossedes,
            'themesPossedes' => $themesPossedes,
            'pulsePoints' => $pulsePoints,
            'recompenses' => $recompenses,
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
            return new Response("L'ancien email est incorrect", 401);
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

    #[Route('/user/{id}/goals', name: 'user_goals')]
    public function manageGoals(int $id, UserRepository $userRepository, GoalRepository $goalRepository): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        $goals = $goalRepository->findBy(['user' => $user]);

        return $this->render('user/manage_goals.html.twig', [
            'user' => $user,
            'goals' => $goals
        ]);
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

        return new JsonResponse(['points' => $user->getTotalPulsePoints()]);
    }
}
