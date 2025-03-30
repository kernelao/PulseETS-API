<?php

namespace App\Entity;

use App\Repository\AvatarRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvatarRepository::class)]
class Avatar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $image;

    #[ORM\Column(type: 'integer')]
    private $pointsRequired; // Points nécessaires pour débloquer

    #[ORM\Column(type: "datetime", nullable: true)]
    private $availableFrom; // Date de début de disponibilité

    #[ORM\Column(type: "datetime", nullable: true)]
    private $availableUntil; // Date de fin de disponibilité

    #[ORM\Column(type: "boolean")]
    private $isEventExclusive; // S'il est exclusif à un événement

    #[ORM\Column(type: "boolean")]
    private $isGoalBased; // S'il est débloqué en fonction d'un objectif

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;
        return $this;
    }

    public function getPointsRequired(): ?int
    {
        return $this->pointsRequired;
    }

    public function setPointsRequired(int $pointsRequired): self
    {
        $this->pointsRequired = $pointsRequired;
        return $this;
    }

    public function getAvailableFrom(): ?\DateTimeInterface
    {
        return $this->availableFrom;
    }

    public function setAvailableFrom(?\DateTimeInterface $availableFrom): self
    {
        $this->availableFrom = $availableFrom;
        return $this;
    }

    public function getAvailableUntil(): ?\DateTimeInterface
    {
        return $this->availableUntil;
    }

    public function setAvailableUntil(?\DateTimeInterface $availableUntil): self
    {
        $this->availableUntil = $availableUntil;
        return $this;
    }

    public function isEventExclusive(): ?bool
    {
        return $this->isEventExclusive;
    }

    public function setIsEventExclusive(bool $isEventExclusive): self
    {
        $this->isEventExclusive = $isEventExclusive;
        return $this;
    }

    public function isGoalBased(): ?bool
    {
        return $this->isGoalBased;
    }

    public function setIsGoalBased(bool $isGoalBased): self
    {
        $this->isGoalBased = $isGoalBased;
        return $this;
    }

    public function isAvailable(): bool
    {
        $now = new \DateTime();
        if ($this->availableFrom && $this->availableFrom > $now) {
            return false;
        }
        if ($this->availableUntil && $this->availableUntil < $now) {
            return false;
        }
        return true; // Retourner explicitement true si aucune des conditions n'est remplie
    }

    public function canUnlock(int $userPoints): bool
    {
        if ($userPoints >= $this->pointsRequired) {
            return true;
        }
        if ($this->isEventExclusive() && $this->isAvailable()) {
            return true;
        }
        if ($this->isGoalBased()) {
            // Implémenter la logique pour vérifier si l'objectif est atteint
            return true; // Retourner true si l'objectif est atteint
        }
        return false; // Retourner false si aucune condition n'est remplie
    }
}