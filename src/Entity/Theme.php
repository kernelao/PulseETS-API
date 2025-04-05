<?php

namespace App\Entity;

use App\Repository\ThemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThemeRepository::class)]
class Theme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 25)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $active = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'themes')]
    private Collection $users;

    /**
     * @var Collection<int, AchatTheme>
     */
    #[ORM\OneToMany(targetEntity: AchatTheme::class, mappedBy: 'theme')]
    private Collection $achatThemes;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->achatThemes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->users->removeElement($user);

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
            $achatTheme->setTheme($this);
        }

        return $this;
    }

    public function removeAchatTheme(AchatTheme $achatTheme): static
    {
        if ($this->achatThemes->removeElement($achatTheme)) {
            // set the owning side to null (unless already changed)
            if ($achatTheme->getTheme() === $this) {
                $achatTheme->setTheme(null);
            }
        }

        return $this;
    }
}
