<?php

namespace App\Entity;

use App\Repository\TacheRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TacheRepository::class)]
class Tache
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')] // ✅ Changement ici pour éviter l'overflow
    #[Groups('tache:read')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le titre est obligatoire.")]
    #[Groups('tache:read')]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups('tache:read')]
    private ?string $description = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups('tache:read')]
    private ?string $tag = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups('tache:read')]
    private ?\DateTimeInterface $dueDate = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups('tache:read')]
    private ?string $priority = null;

    #[ORM\Column]
    #[Groups('tache:read')]
    private bool $completed = false;

    #[ORM\Column]
    #[Groups('tache:read')]
    private bool $pinned = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups('tache:read')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'taches')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    //ajoute
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups('tache:read')]
    private ?\DateTimeInterface $completedAt = null;

    public function __construct()
    {
        $now = new \DateTime();
        $this->createdAt = $now;
        $this->dueDate = $now;
        $this->completed = false;
        $this->pinned = false;
    }

    public function getId(): ?int { return $this->id; }

    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(string $titre): static { $this->titre = $titre; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): static { $this->description = $description; return $this; }

    public function getTag(): ?string { return $this->tag; }
    public function setTag(?string $tag): static { $this->tag = $tag; return $this; }

    public function getDueDate(): ?\DateTimeInterface { return $this->dueDate; }
    public function setDueDate(?\DateTimeInterface $dueDate): static {
        $this->dueDate = $dueDate ?? new \DateTime();
        return $this;
    }

    public function getPriority(): ?string { return $this->priority; }
    public function setPriority(?string $priority): static { $this->priority = $priority; return $this; }

    public function isCompleted(): bool { return $this->completed; }
    public function setCompleted(bool $completed): static { $this->completed = $completed; return $this; }

    public function isPinned(): bool { return $this->pinned; }
    public function setPinned(bool $pinned): static { $this->pinned = $pinned; return $this; }

    public function getCreatedAt(): ?\DateTimeInterface { return $this->createdAt; }
    public function setCreatedAt(\DateTimeInterface $createdAt): static { $this->createdAt = $createdAt; return $this; }

    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): static { $this->user = $user; return $this; }

    //ajoute 
    public function getCompletedAt(): ?\DateTimeInterface
{
    return $this->completedAt;
}

public function setCompletedAt(?\DateTimeInterface $completedAt): static
{
    $this->completedAt = $completedAt;
    return $this;
}
}
