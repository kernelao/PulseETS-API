<?php

namespace App\Entity;

use App\Repository\ReglagesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReglagesRepository::class)]
class Reglages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $pomodoro = null;

    #[ORM\Column(nullable: true)]
    private ?int $courtePause = null;

    #[ORM\Column(nullable: true)]
    private ?int $longuePause = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $theme = null;

    //#[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'reglage')]
    #[ORM\JoinColumn(name: "user_nb_id", referencedColumnName: "id", nullable: false)]
    private ?user $userNb = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPomodoro(): ?int
    {
        return $this->pomodoro;
    }

    public function setPomodoro(?int $pomodoro): static
    {
        $this->pomodoro = $pomodoro;

        return $this;
    }

    public function getCourtePause(): ?int
    {
        return $this->courtePause;
    }

    public function setCourtePause(?int $courtePause): static
    {
        $this->courtePause = $courtePause;

        return $this;
    }

    public function getLonguePause(): ?int
    {
        return $this->longuePause;
    }

    public function setLonguePause(?int $longuePause): static
    {
        $this->longuePause = $longuePause;

        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(?string $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    public function getUserNb(): ?user
    {
        return $this->userNb;
    }

    public function setUserNb(?user $userNb): static
    {
        $this->userNb = $userNb;

        return $this;
    }
}

/*<?php

namespace App\Entity;

use App\Repository\ReglagesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReglagesRepository::class)]
class Reglages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $pomodoro = null;

    #[ORM\Column(name: "courte_pause", nullable: true)]
    private ?int $courtePause = null;

    #[ORM\Column(name: "longue_pause", nullable: true)]
    private ?int $longuePause = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $theme = null;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "user_nb_id", referencedColumnName: "id")]
    private ?User $userNb = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPomodoro(): ?int
    {
        return $this->pomodoro;
    }

    public function setPomodoro(?int $pomodoro): static
    {
        $this->pomodoro = $pomodoro;
        return $this;
    }

    public function getCourtePause(): ?int
    {
        return $this->courtePause;
    }

    public function setCourtePause(?int $courtePause): static
    {
        $this->courtePause = $courtePause;
        return $this;
    }

    public function getLonguePause(): ?int
    {
        return $this->longuePause;
    }

    public function setLonguePause(?int $longuePause): static
    {
        $this->longuePause = $longuePause;
        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(?string $theme): static
    {
        $this->theme = $theme;
        return $this;
    }

    public function getUserNb(): ?User
    {
        return $this->userNb;
    }

    public function setUserNb(?User $userNb): static
    {
        $this->userNb = $userNb;
        return $this;
    }
}
*/

