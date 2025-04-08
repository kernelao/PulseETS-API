<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Avatar;
use App\Entity\Theme;
use App\Entity\AchatAvatar;
use App\Entity\AchatTheme;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\PulsePointService;

class AchatService
{
    private EntityManagerInterface $entityManager;
    private PulsePointService $pulsePointService;

    public function __construct(EntityManagerInterface $entityManager, PulsePointService $pulsePointService)
    {
        $this->entityManager = $entityManager;
        $this->pulsePointService = $pulsePointService;
    }

    public function acheterAvatar(User $user, Avatar $avatar, int $pointsRequired): string
    {
        if ($this->pulsePointService->getTotalPulsePoints($user) >= $pointsRequired) {
            try {
                // Soustraire les points PULSE
                $this->pulsePointService->subtractPulsePoints($user, $pointsRequired);

                // Enregistrer l'achat de l'avatar
                $achatAvatar = new AchatAvatar();
                $achatAvatar->setUser($user);
                $achatAvatar->setAvatar($avatar);
                $this->entityManager->persist($achatAvatar);
                $this->entityManager->flush();

                return 'Avatar acheté avec succès';
            } catch (\Exception $e) {
                return 'Erreur: ' . $e->getMessage();
            }
        }

        return 'Il te manque des points PULSE pour acheter cet avatar';
    }

    public function acheterTheme(User $user, Theme $theme, int $pointsRequired): string
    {
        if ($this->pulsePointService->getTotalPulsePoints($user) >= $pointsRequired) {
            try {
                // Soustraire les points PULSE
                $this->pulsePointService->subtractPulsePoints($user, $pointsRequired);

                // Enregistrer l'achat du thème
                $achatTheme = new AchatTheme();
                $achatTheme->setUser($user);
                $achatTheme->setTheme($theme);
                $this->entityManager->persist($achatTheme);
                $this->entityManager->flush();

                return 'Thème acheté avec succès';
            } catch (\Exception $e) {
                return 'Erreur: ' . $e->getMessage();
            }
        }

        return 'Il te manque des points PULSE pour acheter ce thème';
    }
}
