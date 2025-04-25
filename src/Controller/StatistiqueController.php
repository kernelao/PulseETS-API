<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\TacheRepository;
use App\Repository\PomodoroSessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use DateTimeZone;

#[Route('/api/dashboard')]
class StatistiqueController extends AbstractController
{
    #[Route('', name: 'api_dashboard', methods: ['GET'])]
    public function getStats(
        TacheRepository $tacheRepo,
        PomodoroSessionRepository $pomodoroRepo,
        EntityManagerInterface $em
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'Non authentifié'], 401);
        }

        $userId = $user->getId();
        $username = $user->getUserIdentifier(); // ou getUsername() si défini dans l'entité

        $tz = new DateTimeZone('America/Toronto');
        $today = new DateTime('now', $tz);
        $startOfWeek = (clone $today)->modify('monday this week')->setTime(0, 0, 0);

        $tachesWeek = [];
        $pomodorosWeek = [];

        for ($i = 0; $i < 7; $i++) {
            $day = (clone $startOfWeek)->modify("+$i days");
            $startOfDay = (clone $day)->setTime(0, 0, 0);
            $endOfDay = (clone $day)->setTime(23, 59, 59);

            $tachesWeek[] = $tacheRepo->countCompletedBetween($user, $startOfDay, $endOfDay);
            $pomodorosWeek[] = $pomodoroRepo->countSessionsBetween($user, $startOfDay, $endOfDay);
        }

        $startOfToday = (clone $today)->setTime(0, 0, 0);
        $endOfToday = (clone $today)->setTime(23, 59, 59);

        $tachesToday = $tacheRepo->countCompletedBetween($user, $startOfToday, $endOfToday);
        $pomodorosToday = $pomodoroRepo->countSessionsBetween($user, $startOfToday, $endOfToday);

        $conn = $em->getConnection();

        $streaksTaches = $conn->executeQuery("
            SELECT CONVERT(date, completed_at) AS date, COUNT(*) AS count
            FROM tache
            WHERE user_id = :user AND completed = 1
            GROUP BY CONVERT(date, completed_at)
            ORDER BY date ASC
        ", ['user' => $userId])->fetchAllAssociative();

        $streaksPomodoro = $conn->executeQuery("
            SELECT CONVERT(date, started_at) AS date, COUNT(*) AS count
            FROM pomodoro_session
            WHERE user_id = :user
            GROUP BY CONVERT(date, started_at)
            ORDER BY date ASC
        ", ['user' => $userId])->fetchAllAssociative();

        return new JsonResponse([
            'tachesWeek' => $tachesWeek,
            'pomodorosWeek' => $pomodorosWeek,
            'tachesToday' => $tachesToday,
            'pomodorosToday' => $pomodorosToday,
            'streaksTaches' => $streaksTaches,
            'streaksPomodoro' => $streaksPomodoro,
            'user' => [
                'username' => $username,
            ],
        ]);
    }
}
