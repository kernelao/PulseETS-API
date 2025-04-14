<?php

namespace App\Controller;

use App\Repository\TacheRepository;
use App\Repository\PomodoroSessionRepository;
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
        PomodoroSessionRepository $pomodoroRepo
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['message' => 'Non authentifié'], 401);
        }

        $tz = new DateTimeZone('America/Toronto');
        $today = new DateTime('now', $tz);
        $startOfWeek = (clone $today)->modify('monday this week')->setTime(0, 0, 0);
        $endOfWeek = (clone $startOfWeek)->modify('+6 days')->setTime(23, 59, 59);

        $tachesWeek = [];
        $pomodorosWeek = [];

        // Generate counts for each day (Lundi to Dimanche)
        for ($i = 0; $i < 7; $i++) {
            $day = (clone $startOfWeek)->modify("+$i days");
            $startOfDay = (clone $day)->setTime(0, 0, 0);
            $endOfDay = (clone $day)->setTime(23, 59, 59);

            $tachesCount = $tacheRepo->countCompletedBetween($user, $startOfDay, $endOfDay);
            $pomodoroCount = $pomodoroRepo->countSessionsBetween($user, $startOfDay, $endOfDay);

            $tachesWeek[] = $tachesCount;
            $pomodorosWeek[] = $pomodoroCount;
        }

        // For today
        $startOfToday = (clone $today)->setTime(0, 0, 0);
        $endOfToday = (clone $today)->setTime(23, 59, 59);

        $tachesToday = $tacheRepo->countCompletedBetween($user, $startOfToday, $endOfToday);
        $pomodorosToday = $pomodoroRepo->countSessionsBetween($user, $startOfToday, $endOfToday);

        return new JsonResponse([
            'tachesWeek' => $tachesWeek,
            'pomodorosWeek' => $pomodorosWeek,
            'tachesToday' => $tachesToday,
            'pomodorosToday' => $pomodorosToday
        ]);
    }
}
