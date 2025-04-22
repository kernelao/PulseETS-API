<?php

namespace App\Controller;

use App\Entity\PomodoroSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Service\RecompenseService;
use App\Repository\PomodoroSessionRepository;

#[Route('/api/pomodoro-session')]
class PomodoroSessionController extends AbstractController
{
    #[Route('', name: 'create_pomodoro_session', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function create(Request $request, EntityManagerInterface $em, PomodoroSessionRepository $pomodoroSessionRepository, RecompenseService $recompenseService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Récupérer l'utilisateur connecté via JWT
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'Utilisateur non authentifié'], 401);
        }

        try {
            $timezone = new \DateTimeZone('America/Toronto');

            $session = new PomodoroSession();
            $session->setUser($user); // 👤 Associer l'utilisateur

            // 🕒 Début & Fin
            $session->setStartedAt(new \DateTimeImmutable($data['startedAt'], $timezone));
            if (!empty($data['endedAt'])) {
                $session->setEndedAt(new \DateTimeImmutable($data['endedAt'], $timezone));
            }

            // 🔢 Pomodoros complétés
            $session->setPomodorosCompletes($data['pomodoros_completes'] ?? 1);

            // ⚙️ AutoStart + paramètres optionnels
            $session->setAutoStart($data['autoStart'] ?? false);
            $session->setPomodoroDuration($data['pomodoroDuration'] ?? null);
            $session->setShortBreak($data['shortBreak'] ?? null);
            $session->setLongBreak($data['longBreak'] ?? null);

            //if ($session->getPomodoroDuration() < 1500) {
              //  return $this->json([
                //    'status' => 'error',
                  //  'message' => 'Session trop courte pour être validée (min. 25 minutes)'
              //  ], 400);
            //}

            $em->persist($session);
            $em->flush();

            $nbSessions = $pomodoroSessionRepository->count(['user' => $user]);

            $recompenseService->verifierEtDebloquerRecompenses($user, [
                'sessionsCompletees' => $nbSessions
        ]);

            return $this->json([
                'status' => 'ok',
                'id' => $session->getId()
            ], 201);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}