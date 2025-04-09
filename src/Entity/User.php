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
use App\Entity\PulsePoint;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
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

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Tache::class, orphanRemoval: true)]
    private Collection $taches;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: AchatAvatar::class)]
    private Collection $achatsAvatars;    

    #[ORM\OneToOne(targetEntity: AchatAvatar::class)]
    private ?AchatAvatar $avatarPrincipal = null;

    /**
     * @var Collection<int, AchatTheme>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: AchatTheme::class)]
    private Collection $achatThemes;


    #[ORM\OneToMany(mappedBy: 'user', targetEntity: PulsePoint::class, cascade: ['persist', 'remove'])]
    private Collection $pulsePoints;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Recompense::class)]
    private Collection $recompenses;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Goal::class)]
    private Collection $unlockedGoals;


    
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->taches = new ArrayCollection(); // 👈 Initialisation de la collection
        $this->achatsAvatars = new ArrayCollection();
        $this->unlockedGoals = new ArrayCollection();
        $this->achatThemes = new ArrayCollection();
        $this->pulsePoints = new ArrayCollection();
        $this->recompenses = new ArrayCollection();
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

    public function getAchatsAvatars(): Collection
    {
        return $this->achatsAvatars;
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

    /**
     * @return Collection<int, AchatTheme>
     */
    public function getAchatThemes(): Collection
    {
        return $this->achatThemes;
    }

    public function addAchatTheme(AchatTheme $achatTheme): static
    {
        if (!$this->achatThemes->contains($achatTheme)) {
            $this->achatThemes->add($achatTheme);
            $achatTheme->setUser($this);
        }

        return $this;
    }

    public function removeAchatTheme(AchatTheme $achatTheme): static
    {
        if ($this->achatThemes->removeElement($achatTheme)) {
            // set the owning side to null (unless already changed)
            if ($achatTheme->getUser() === $this) {
                $achatTheme->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PulsePoint>
    */
    public function getPulsePoints(): Collection
    {
        return $this->pulsePoints;
    }

    public function addPulsePoint(PulsePoint $pulsePoint): self
    {
        if (!$this->pulsePoints->contains($pulsePoint)) {
            $this->pulsePoints[] = $pulsePoint;
            $pulsePoint->setUser($this);
        }

        return $this;
    }

    public function removePulsePoint(PulsePoint $pulsePoint): self
    {
        if ($this->pulsePoints->removeElement($pulsePoint)) {
        }

        return $this;
    }

    public function getTotalPulsePoints(): int
    {
        $total = 0;
        foreach ($this->pulsePoints as $pulsePoint) {
            $total += $pulsePoint->getPoints();
        }
        return $total;
    }

    public function getRecompenses(): Collection
    {
        return $this->recompenses;
    }

}