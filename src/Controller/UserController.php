<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\AchatAvatar;
use App\Entity\Goal;
use App\Repository\UserRepository;
use App\Repository\AchatAvatarRepository;
use App\Repository\GoalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{
    private $userRepository;
    private $achatAvatarRepository;
    private $goalRepository;
    private $entityManager;

    public function __construct(
        UserRepository $userRepository,
        AchatAvatarRepository $achatAvatarRepository,
        GoalRepository $goalRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->userRepository = $userRepository;
        $this->achatAvatarRepository = $achatAvatarRepository;
        $this->goalRepository = $goalRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/user/{id}', name: 'user_profile')]
    public function showUserProfile(int $id): Response
    {
        // Récupérer l'utilisateur par son ID
        $user = $this->userRepository->find($id);

        // Si l'utilisateur n'existe pas, rediriger vers une page d'erreur
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        // Récupérer les achats d'avatars et les objectifs de l'utilisateur
        $achatsAvatars = $this->achatAvatarRepository->findBy(['user' => $user]);
        $goals = $this->goalRepository->findBy(['user' => $user]);

        // Renvoyer la vue avec l'utilisateur, ses achats d'avatars et ses objectifs
        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'achatsAvatars' => $achatsAvatars,
            'goals' => $goals
        ]);
    }

    #[Route('/user/{id}/add_goal', name: 'user_add_goal', methods: ['POST'])]
    public function addGoal(Request $request, int $id): Response
    {
        // Récupérer l'utilisateur
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        // Créer un nouvel objectif
        $goal = new Goal();
        $goal->setUser($user);
        $goal->setDescription($request->request->get('description'));
        $goal->setCompleted(false);
        $goal->setDateCreated(new \DateTime());

        // Enregistrer l'objectif en base de données
        $this->entityManager->persist($goal);
        $this->entityManager->flush();

        // Rediriger vers le profil de l'utilisateur
        return $this->redirectToRoute('user_profile', ['id' => $user->getId()]);
    }

    #[Route('/user/{id}/buy_avatar', name: 'user_buy_avatar', methods: ['POST'])]
    public function buyAvatar(Request $request, int $id): Response
    {
        // Récupérer l'utilisateur
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        // Créer un achat d'avatar
        $achatAvatar = new AchatAvatar();
        $achatAvatar->setUser($user);
        $achatAvatar->setDateAchat(new \DateTime());

        // Enregistrer l'achat d'avatar en base de données
        $this->entityManager->persist($achatAvatar);
        $this->entityManager->flush();

        // Rediriger vers le profil de l'utilisateur
        return $this->redirectToRoute('user_profile', ['id' => $user->getId()]);
    }

    #[Route('/user/{id}/change_avatar', name: 'user_change_avatar', methods: ['POST'])]
    public function changeAvatar(Request $request, int $id): Response
    {
        // Récupérer l'utilisateur
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        // Récupérer l'ID de l'avatar sélectionné par l'utilisateur
        $avatarId = $request->request->get('avatar_id');
        $avatar = $this->achatAvatarRepository->find($avatarId);

        // Si l'avatar existe, le mettre à jour comme avatar principal
        if ($avatar) {
            $user->setAvatarPrincipal($avatar);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        // Rediriger vers le profil de l'utilisateur
        return $this->redirectToRoute('user_profile', ['id' => $user->getId()]);
    }


    // Méthode pour afficher une page de gestion des objectifs (si nécessaire)
    #[Route('/user/{id}/goals', name: 'user_goals')]
    public function manageGoals(int $id): Response
    {
        // Récupérer l'utilisateur et ses objectifs
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }
        $goals = $this->goalRepository->findBy(['user' => $user]);

        // Afficher les objectifs
        return $this->render('user/manage_goals.html.twig', [
            'user' => $user,
            'goals' => $goals
        ]);
    }
}
