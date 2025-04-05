<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AvatarRepository;


#[ORM\Entity(repositoryClass: AvatarRepository::class)]
#[ORM\Table(name: 'avatar')]
class Avatar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    /**
     * @var Collection<int, AchatAvatar>
     */
    #[ORM\OneToMany(targetEntity: AchatAvatar::class, mappedBy: 'avatar')]
    private Collection $achats;

    #[ORM\Column(type: 'boolean')]
    private bool $active;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'avatars')]
    private Collection $users;

    public function __construct()
    {
        $this->achats = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;
        return $this;
    }

    public function getAchats(): Collection
    {
        return $this->achats;
    }

    public function addAchat(AchatAvatar $achat): self
    {
        if (!$this->achats->contains($achat)) {
            $this->achats->add($achat);
            $achat->setAvatar($this);
        }

        return $this;
    }

    public function removeAchat(AchatAvatar $achat): self
    {
        if ($this->achats->removeElement($achat)) {
            if ($achat->getAvatar() === $this) {
                $achat->setAvatar(null);
            }
        }

        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

           return $this;
    }
}
