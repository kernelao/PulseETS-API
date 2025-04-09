<?php

namespace App\Controller;

use App\Entity\Avatar;
use App\Entity\Theme;
use App\Service\AchatService;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Doctrine\ORM\EntityManagerInterface;
class AchatController extends AbstractController
{
    private AchatService $achatService;

    public function __construct(AchatService $achatService)
    {
        $this->achatService = $achatService;
    }

    #[Route('/api/user/avatars/{id}/buy', name: 'acheter_avatar', methods: ['POST'])]
    public function acheterAvatar(Avatar $avatar): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            throw new AccessDeniedException("Tu dois être connecté pour acheter un avatar.");
        }

        $message = $this->achatService->acheterAvatar($user, $avatar);

        return new JsonResponse(
            ['message' => $message],
            str_contains($message, 'succès') ? JsonResponse::HTTP_OK : JsonResponse::HTTP_BAD_REQUEST
        );
    }

    #[Route('/api/user/themes/{id}/buy', name: 'acheter_theme', methods: ['POST'])]
    public function acheterTheme(Theme $theme): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            throw new AccessDeniedException("Tu dois être connecté pour acheter un thème.");
        }

        $message = $this->achatService->acheterTheme($user, $theme);

        return new JsonResponse(
            ['message' => $message],
            str_contains($message, 'succès') ? JsonResponse::HTTP_OK : JsonResponse::HTTP_BAD_REQUEST
        );
    }

    #[Route('/api/user/theme/apply/{id}', name: 'apply_theme', methods: ['POST'])]
public function applyTheme(int $id, EntityManagerInterface $em): JsonResponse
{
    $user = $this->getUser();

    if (!$user instanceof User) {
        return new JsonResponse(['message' => 'Utilisateur non connecté.'], 403);
    }

    $theme = $em->getRepository(Theme::class)->find($id);
    if (!$theme) {
        return new JsonResponse(['message' => 'Thème introuvable.'], 404);
    }

    $owned = $user->getAchatThemes()->exists(fn($key, $achat) => $achat->getTheme() === $theme);
    if (!$owned) {
        return new JsonResponse(['message' => 'Thème non acheté.'], 403);
    }

    // On pourrait avoir un champ "themeActif" si tu veux le garder en base
    // $user->setThemeActif($theme); // À créer si souhaité

    $em->flush();

    return new JsonResponse(['message' => 'Thème appliqué avec succès.']);
}

}
