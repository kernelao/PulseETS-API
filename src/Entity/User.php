<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\AchatAvatar;
use App\Entity\Goal;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $email;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private ?string $username = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'integer')]
    private int $pulsePoints = 0;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Tache::class, orphanRemoval: true)]
    private Collection $taches;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: AchatAvatar::class, orphanRemoval: true)]
    private Collection $achatAvatar;

    #[ORM\ManyToMany(targetEntity: Goal::class)]
    private Collection $unlockedGoals;

    #[ORM\ManyToOne(targetEntity: AchatAvatar::class)]
    #[ORM\JoinColumn(name: "avatar_id", referencedColumnName: "id")]
    private ?AchatAvatar $avatarPrincipal = null;

    #[ORM\ManyToMany(targetEntity: Avatar::class, inversedBy: 'users')]
    private Collection $avatars;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->taches = new ArrayCollection();
        $this->achatAvatar = new ArrayCollection();
        $this->unlockedGoals = new ArrayCollection();
        $this->avatars = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function eraseCredentials(): void
    {
    }

    public function getTaches(): Collection
    {
        return $this->taches;
    }

    public function addTache(Tache $tache): self
    {
        if (!$this->taches->contains($tache)) {
            $this->taches[] = $tache;
            $tache->setUser($this);
        }

        return $this;
    }

    public function removeTache(Tache $tache): self
    {
        if ($this->taches->removeElement($tache)) {
            if ($tache->getUser() === $this) {
                $tache->setUser(null);
            }
        }

        return $this;
    }

    public function getPulsePoints(): int
    {
        return $this->pulsePoints;
    }

    public function setPulsePoints(int $points): self
    {
        $this->pulsePoints = $points;
        return $this;
    }

    public function addPulsePoints(int $points): self
    {
        $this->pulsePoints += $points;
        return $this;
    }

    public function getAchatsAvatars(): Collection
    {
        return $this->achatAvatar;
    }

    public function getGoals(): Collection
    {
        return $this->unlockedGoals;
    }

    public function getAvatarPrincipal(): ?AchatAvatar
    {
        return $this->avatarPrincipal;
    }

    public function setAvatarPrincipal(?AchatAvatar $avatarPrincipal): self
    {
        $this->avatarPrincipal = $avatarPrincipal;
        return $this;
    }

    public function getAvatars(): Collection
    {
        return $this->avatars;
    }

    public function addAvatar(Avatar $avatar): self
    {
        if (!$this->avatars->contains($avatar)) {
            $this->avatars->add($avatar);
        }

        return $this;
    }

    public function removeAvatar(Avatar $avatar): self
    {
        $this->avatars->removeElement($avatar);
        return $this;
    }
}
