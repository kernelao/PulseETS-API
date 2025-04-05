<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Avatar;
use App\Entity\Theme;
use App\Entity\AchatAvatar;
use App\Entity\AchatTheme;
use App\Entity\PulsePoint;
use App\Repository\GoalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class AchatController extends AbstractController
{
    private $entityManager;
    private $goalRepository;

    public function __construct(EntityManagerInterface $entityManager, GoalRepository $goalRepository)
    {
        $this->entityManager = $entityManager;
        $this->goalRepository = $goalRepository;
    }

    #[Route('/api/user/avatars/{avatarId}/buy', name: 'buy_avatar', methods: ['POST'])]
    public function buyAvatar(int $avatarId, User $user): JsonResponse
    {
        // Récupérer l'avatar
        $avatar = $this->entityManager->getRepository(Avatar::class)->find($avatarId);
        if (!$avatar) {
            return new JsonResponse(['message' => 'Avatar ne peut pas être trouvé'], 404);
        }

        // Vérifier si l'avatar est déjà acheté
        if ($user->getAvatars()->contains($avatar)) {
            return new JsonResponse(['message' => 'Tu as déjà cet avatar'], 400);
        }

        // Vérifier les points PULSE de l'utilisateur
        $pointsRequired = 100; // Exemple de prix en points PULSE
        if ($user->getTotalPulsePoints() >= $pointsRequired) {
            // Acheter avec des points PULSE
            $pulsePoint = new PulsePoint($user, 100); // Crée un objet avec 100 points
            $this->entityManager->persist($pulsePoint);
            
            // Créer un nouvel objet PulsePoint pour enregistrer l'achat
            $pulsePoint = new PulsePoint($user, -$pointsRequired); // On enlève les points
            $this->entityManager->persist($pulsePoint);

            // Enregistrer l'achat d'avatar
            $achatAvatar = new AchatAvatar();
            $achatAvatar->setUser($user);
            $achatAvatar->setAvatar($avatar);
            $this->entityManager->persist($achatAvatar);
            
            // Ajouter l'avatar à l'utilisateur
            $user->addAvatar($avatar);

            $total = $user->getTotalPulsePoints(); // ✔️ Correct (car méthode définie dans User)


            $this->entityManager->flush();

            return new JsonResponse(['message' => 'Avatar acheté avec tes points PULSE'], 200);
        }

        // Vérifier si un objectif a été atteint
        $goal = $this->goalRepository->findAvailableGoalForUser($user);
        if ($goal && $goal->isAchieved()) {
            // Acheter avec l'objectif atteint
            $user->addAvatar($avatar);
            
            // Créer un nouvel objet PulsePoint si nécessaire pour l'objectif
            $pulsePoint = new PulsePoint($user, 50); // Exemple : ajouter 50 points PULSE pour l'objectif
            $this->entityManager->persist($pulsePoint);

            // Enregistrer l'achat d'avatar
            $achatAvatar = new AchatAvatar();
            $achatAvatar->setUser($user);
            $achatAvatar->setAvatar($avatar);
            $achatAvatar->setDateAchat(new \DateTime());
            $this->entityManager->persist($achatAvatar);

            $this->entityManager->flush();

            return new JsonResponse(['message' => 'Avatar acheté grâce au défi relevé'], 200);
        }

        // Si l'utilisateur n'a pas assez de points PULSE et n'a pas atteint d'objectif
        return new JsonResponse(['message' => 'Il te manque des points PULSE ou il faut que tu complète un défi'], 400);
    }

    #[Route('/api/user/themes/{themeId}/buy', name: 'buy_theme', methods: ['POST'])]
    public function buyTheme(int $themeId, User $user): JsonResponse
    {
        // Récupérer le thème
        $theme = $this->entityManager->getRepository(Theme::class)->find($themeId);
        if (!$theme) {
            return new JsonResponse(['message' => 'Thème ne peut pas être trouvé'], 404);
        }

        // Vérifier si le thème est déjà acheté
        if ($user->getThemes()->contains($theme)) {
            return new JsonResponse(['message' => 'Tu as déjà ce thème'], 400);
        }

        // Vérifier les points PULSE de l'utilisateur
        $pointsRequired = 200; // Exemple de prix en points PULSE
        if ($user->getPulsePoints() >= $pointsRequired) {
            // Acheter avec des points PULSE
            $pulsePoint = new PulsePoint($user, 50);
            $user->addPulsePoint($pulsePoint); // ✔️ Correct
            
            // Créer un nouvel objet PulsePoint pour enregistrer l'achat
            $pulsePoint = new PulsePoint($user, -$pointsRequired); // On enlève les points
            $this->entityManager->persist($pulsePoint);

            // Enregistrer l'achat de thème
            $achatTheme = new AchatTheme();
            $achatTheme->setUser($user);
            $achatTheme->setTheme($theme);
            $achatTheme->setDateAchat(new \DateTime());
            $this->entityManager->persist($achatTheme);

            // Ajouter le thème à l'utilisateur
            $user->addTheme($theme);

            $this->entityManager->flush();

            return new JsonResponse(['message' => 'Thème acheté avec tes points PULSE'], 200);
        }

        // Vérifier si un objectif a été atteint
        $goal = $this->goalRepository->findAvailableGoalForUser($user);
        if ($goal && $goal->isAchieved()) {
            // Acheter avec l'objectif atteint
            $user->addTheme($theme);

            // Créer un nouvel objet PulsePoint si nécessaire pour l'objectif
            $pulsePoint = new PulsePoint($user, 50); // Exemple : ajouter 50 points PULSE pour l'objectif
            $this->entityManager->persist($pulsePoint);

            // Enregistrer l'achat de thème
            $achatTheme = new AchatTheme();
            $achatTheme->setUser($user);
            $achatTheme->setTheme($theme);
            $achatTheme->setDateAchat(new \DateTime());
            $this->entityManager->persist($achatTheme);

            $this->entityManager->flush();

            return new JsonResponse(['message' => 'Thème acheté grâce au défi relevé'], 200);
        }

        // Si l'utilisateur n'a pas assez de points PULSE et n'a pas atteint d'objectif
        return new JsonResponse(['message' => 'Il te manque des points PULSE ou il faut que tu complète un défi'], 400);
    }
}
