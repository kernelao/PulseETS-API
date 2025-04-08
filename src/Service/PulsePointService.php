<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\PulsePoint;
use Doctrine\ORM\EntityManagerInterface;

class PulsePointService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addPulsePoints(User $user, int $points): void
    {
        $pulsePoint = new PulsePoint();
        $pulsePoint->setPoints($points);
        $pulsePoint->setUser($user);
        $user->getPulsePoints()->add($pulsePoint);

        $this->entityManager->persist($pulsePoint);
        $this->entityManager->flush();
    }

    public function subtractPulsePoints(User $user, int $points): void
    {
        $currentPoints = $this->getTotalPulsePoints($user);
        if ($currentPoints < $points) {
            throw new \Exception("L'utilisateur n'a pas assez de points.");
        }

        $pulsePoint = new PulsePoint();
        $pulsePoint->setPoints(-$points);
        $pulsePoint->setUser($user);
        $user->getPulsePoints()->add($pulsePoint);

        $this->entityManager->persist($pulsePoint);
        $this->entityManager->flush();
    }

    public function getTotalPulsePoints(User $user): int
    {
        $total = 0;
        foreach ($user->getPulsePoints() as $pulsePoint) {
            $total += $pulsePoint->getPoints();
        }
        return $total;
    }
}
