<?php

namespace App\Entity;

use App\Repository\AideRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\User;

#[ORM\Entity(repositoryClass: AideRepository::class)]
class Aide
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['aide:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['aide:read'])]
    private ?string $objet = null;

    #[ORM\Column(type: 'text')]
    #[Groups(['aide:read'])]
    private ?string $contenu = null;

    #[ORM\Column(length: 50)]
    #[Groups(['aide:read'])]
    private ?string $type = null;

    #[ORM\Column]
    #[Groups(['aide:read'])]
    private ?int $priorite = null;

    #[ORM\Column(length: 50)]
    #[Groups(['aide:read'])]
    private ?string $statut = 'nouveau';

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['aide:read'])]
    private ?string $reponse = null;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['aide:read'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Groups(['aide:read'])]
    private ?User $user = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(string $objet): self
    {
        $this->objet = $objet;
        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getPriorite(): ?int
    {
        return $this->priorite;
    }

    public function setPriorite(int $priorite): self
    {
        $this->priorite = $priorite;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(?string $reponse): self
    {
        $this->reponse = $reponse;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
}
