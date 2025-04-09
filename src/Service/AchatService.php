<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Avatar;
use App\Entity\Theme;
use App\Entity\AchatAvatar;
use App\Entity\AchatTheme;
use App\Entity\PulsePoint;
use Doctrine\ORM\EntityManagerInterface;

class AchatService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function acheterAvatar(User $user, Avatar $avatar): string
    {
        $pointsRequired = $this->getAvatarCost($avatar->getName());

        foreach ($user->getAchatsAvatars() as $achat) {
            if ($achat->getAvatar()->getId() === $avatar->getId()) {
                return "Tu as déjà acheté cet avatar.";
            }
        }

        if ($user->getTotalPulsePoints() < $pointsRequired) {
            return "Tu n’as pas assez de points PULSE.";
        }

        $pulsePoint = new PulsePoint();
        $pulsePoint->setUser($user);
        $pulsePoint->setPoints(-$pointsRequired);
        $pulsePoint->setDateCreated(new \DateTime());
        $this->entityManager->persist($pulsePoint);

        $achatAvatar = new AchatAvatar();
        $achatAvatar->setUser($user);
        $achatAvatar->setAvatar($avatar);
        $achatAvatar->setDateAchat(new \DateTime());
        $this->entityManager->persist($achatAvatar);

        $user->setAvatarPrincipal($achatAvatar);

        $this->entityManager->flush();

        return "Avatar acheté avec succès 🎉";
    }

    public function acheterTheme(User $user, Theme $theme): string
    {
        $pointsRequired = $this->getThemeCost($theme->getName());

        foreach ($user->getAchatThemes() as $achat) {
            if ($achat->getTheme()->getId() === $theme->getId()) {
                return "Tu as déjà acheté ce thème.";
            }
        }

        if ($user->getTotalPulsePoints() < $pointsRequired) {
            return "Tu n’as pas assez de points PULSE.";
        }

        $pulsePoint = new PulsePoint();
        $pulsePoint->setUser($user);
        $pulsePoint->setPoints(-$pointsRequired);
        $pulsePoint->setDateCreated(new \DateTime());
        $this->entityManager->persist($pulsePoint);

        $achatTheme = new AchatTheme();
        $achatTheme->setUser($user);
        $achatTheme->setTheme($theme);
        $achatTheme->setDateAchat(new \DateTime());
        $this->entityManager->persist($achatTheme);

        $this->entityManager->flush();

        return "Thème acheté avec succès 🎉";
    }

    private function getAvatarCost(string $name): int
    {
        return match ($name) {
            "Jon Doe" => 0,
            "Lina", "Grey Kid", "Sequelita" => 100,
            "Incognita", "Roussette" => 150,
            "Julie", "Kim Possible" => 200,
            "Cool guy", "Suit man" => 250,
            "Wild n Serious", "80's boy" => 300,
            "Wild n serious on vacation", "Donna" => 3000,
            "Neon Lights" => 400,
            "Steampunk Voyager" => 600,
            "Space Explorer" => 700,
            "Viking Warrior" => 750,
            "Wizard Supreme" => 1000,
            "I am Batman", "Suuuuuu", "Elektra", "Tony Stark aka Ironman", "Why so serious?" => 25000,
            default => 100,
        };
    }

    private function getThemeCost(string $name): int
    {
        return match ($name) {
            "Dark Mode" => 200,
            "Cyberpunk" => 300,
            "Minimalist" => 250,
            "Vintage" => 350,
            "Neon Lights" => 400,
            "Steampunk" => 500,
            "Space" => 700,
            default => 300,
        };
    }
}
