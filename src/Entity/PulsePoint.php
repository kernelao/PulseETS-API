<?php

namespace App\Entity;

use App\Repository\PulsePointRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PulsePointRepository::class)]
class PulsePoint
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'pulsePoints')]
    #[ORM\JoinColumn(nullable: false)] // La clé étrangère ne peut pas être nulle
    private User $user;

    #[ORM\Column(type: 'integer')]
    private int $points;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $dateCreated;

    public function __construct(User $user, int $points)
    {
        $this->user = $user;
        $this->points = $points;
        $this->dateCreated = new \DateTime(); // Date de création de l'objet PulsePoint
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function setPoints(int $points): self
    {
        $this->points = $points;
        return $this;
    }

    public function getDateCreated(): \DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    /**
     * Retourne une chaîne de caractères représentant l'objet
     */
    public function __toString(): string
    {
        return sprintf('User ID: %d, Points: %d, Date: %s', $this->user->getId(), $this->points, $this->dateCreated->format('Y-m-d H:i:s'));
    }
}
