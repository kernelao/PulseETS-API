<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\PulsePoint;
use App\Entity\User;
use App\Entity\Element;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\AchatRepository;
use App\Repository\PulsePointRepository;
use App\Service\RecompenseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/user')]
class UserController extends AbstractController
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $em
    ) {}

    #[Route('/profile', name: 'get_user_profile', methods: ['GET'])]
    public function profile(
        
        AchatRepository $achatRepository,
        PulsePointRepository $pulseRepo
        ): JsonResponse {
            
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'Non authentifié'], 401);
        }

        // Points accumulés
        $pulsePoints = $pulseRepo->getTotalPointsForUser($user);

        // Achats (avatars et thèmes)
        $achats = $achatRepository->findBy(['utilisateur' => $user]);
        $avatars = [];
        $themes = [];

        foreach ($achats as $achat) {
            $element = $achat->getElement();
            if ($element->getType() === 'avatar') {
                $avatars[] = $element->getName();
            } elseif ($element->getType() === 'theme') {
                $themes[] = $element->getName();
            }
        }

        return new JsonResponse([
            'email' => $user->getEmail(),
            'pulsePoints' => $pulsePoints,
            'unlockedAvatars' => $avatars,
            'unlockedThemes' => $themes,
            'themeName' => $user->getThemeName(),
            'avatarPrincipal' => $user->getAvatarPrincipal()?->getName(),
            'themeName' => $user->getThemeName(),

        ]);
    }

    #[Route('/pulsepoints/add', name: 'add_pulse_points', methods: ['POST'])]
    public function addPulsePoints(Request $request): JsonResponse
    {
        $user = $this->security->getUser();

        if (!$user) {
            return new JsonResponse(['message' => 'Utilisateur non authentifié'], 401);
        }

        $data = json_decode($request->getContent(), true);
        $points = $data['points'] ?? null;

        if (!is_numeric($points)) {
            return new JsonResponse(['message' => 'Nombre de points invalide'], 400);
        }

        $pulsePoint = new PulsePoint();
        $pulsePoint->setUtilisateur($user);
        $pulsePoint->setPoints((int) $points);
        $pulsePoint->setDateCreated(new \DateTimeImmutable());

        $this->em->persist($pulsePoint);
        $this->em->flush();

        return new JsonResponse(['message' => 'Points ajoutés avec succès'], 201);
    }

    #[Route('/theme/apply', name: 'apply_theme', methods: ['POST'])]
public function applyTheme(Request $request, AchatRepository $achatRepo): JsonResponse
{
    $user = $this->getUser();

    if (!$user instanceof User) {
        return new JsonResponse(['message' => 'Non authentifié'], 401);
    }

    $data = json_decode($request->getContent(), true);
    $themeName = $data['themeName'] ?? null;

    if (!$themeName) {
        return new JsonResponse(['message' => 'Nom du thème manquant'], 400);
    }

    $achats = $achatRepo->findBy(['utilisateur' => $user]);

    $ownedThemeNames = array_map(function ($achat) {
        return $achat->getElement()?->getName();
    }, array_filter($achats, fn($achat) => $achat->getElement()?->getType() === 'theme'));

    if (!in_array($themeName, $ownedThemeNames)) {
        return new JsonResponse(['message' => 'Thème non débloqué'], 403);
    }

    $user->setThemeName($themeName);
    $this->em->flush();

    return new JsonResponse(['message' => 'Thème appliqué avec succès']);
}


    #[Route('/avatar/principal', name: 'change_avatar_principal', methods: ['PUT'])]
public function changeAvatarPrincipal(
    Request $request,
    AchatRepository $achatRepository,
    EntityManagerInterface $em
): JsonResponse {
    $user = $this->getUser();

    if (!$user instanceof User) {
        return new JsonResponse(['message' => 'Non authentifié'], 401);
    }

    $data = json_decode($request->getContent(), true);
    $avatarName = $data['avatarName'] ?? null;

    if (!$avatarName) {
        return new JsonResponse(['message' => 'Nom de l\'avatar manquant'], 400);
    }

    $achats = $achatRepository->findBy(['utilisateur' => $user]);

    foreach ($achats as $achat) {
        $element = $achat->getElement();
        if ($element->getType() === 'avatar' && $element->getName() === $avatarName) {
            $user->setAvatarPrincipal($element);
            $em->flush();

            return new JsonResponse(['message' => 'Avatar principal mis à jour avec succès']);
        }
    }

    return new JsonResponse(['message' => 'Avatar non trouvé ou non débloqué'], 404);
}


    #[Route('/recompenses/check', name: 'check_recompenses', methods: ['POST'])]
    public function checkRecompenses(Request $request, RecompenseService $recompenseService): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'Utilisateur non authentifié'], 401);
        }

        $data = json_decode($request->getContent(), true);

        $notes = $data['notesAjoutees'] ?? 0;
        $taches = $data['tachesCompletees'] ?? 0;
        $sessions = $data['sessionsCompletees'] ?? 0;

        $recompenses = $recompenseService->verifierEtDebloquerRecompenses($user, [
            'notesAjoutees' => $notes,
            'tachesCompletees' => $taches,
            'sessionsCompletees' => $sessions,
        ]);

        return new JsonResponse([
            'message' => 'Vérification terminée',
            'nouvellesRecompenses' => array_map(fn($r) => $r->getNom(), $recompenses),
        ]);
    }

    #[Route('/change-password', name: 'change_password', methods: ['POST'])]
public function changePassword(
    Request $request,
    UserPasswordHasherInterface $passwordHasher,
    EntityManagerInterface $em
): JsonResponse {
    $user = $this->getUser();

    if (!$user instanceof User) {
        return new JsonResponse(['message' => 'Non authentifié'], 401);
    }

    $data = json_decode($request->getContent(), true);
    $oldPsw = $data['oldPsw'] ?? null;
    $newPsw = $data['newPsw'] ?? null;

    if (!$oldPsw || !$newPsw) {
        return new JsonResponse(['message' => 'Champs requis manquants'], 400);
    }

    if (!$passwordHasher->isPasswordValid($user, $oldPsw)) {
        return new JsonResponse(['message' => 'Ancien mot de passe incorrect'], 401);
    }

    $encodedPassword = $passwordHasher->hashPassword($user, $newPsw);
    $user->setPassword($encodedPassword);
    $em->flush();

    return new JsonResponse(['message' => 'Mot de passe modifié avec succès']);
}

#[Route('/change-email', name: 'change_email', methods: ['POST'])]
public function changeEmail(
    Request $request,
    EntityManagerInterface $em
): JsonResponse {
    $user = $this->getUser();

    if (!$user instanceof User) {
        return new JsonResponse(['message' => 'Non authentifié'], 401);
    }

    $data = json_decode($request->getContent(), true);
    $oldEmail = $data['oldEmail'] ?? null;
    $newEmail = $data['newEmail'] ?? null;

    if (!$oldEmail || !$newEmail) {
        return new JsonResponse(['message' => 'Champs requis manquants'], 400);
    }

    if ($oldEmail !== $user->getEmail()) {
        return new JsonResponse(['message' => 'Ancien courriel incorrect'], 401);
    }

    $user->setEmail($newEmail);
    $em->flush();

    return new JsonResponse(['message' => 'Courriel mis à jour avec succès']);
}


}
