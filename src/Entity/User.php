<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

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

    /**
     * @var Collection<int, Note>
     */
    #[ORM\OneToMany(targetEntity: Note::class, mappedBy: 'utilisateur')]
    private Collection $notes; 

      /**
     * @var Collection<int, PomodoroSession>
     */
    #[ORM\OneToMany(targetEntity: PomodoroSession::class, mappedBy: 'user')]
    private Collection $pomodoroSessions;
      
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->taches = new ArrayCollection(); 
        $this->notes = new ArrayCollection();
        $this->pomodoroSessions = new ArrayCollection();
      

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
        return $this->email;
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

    /**
     * @return Collection<int, Note>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): static
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setUtilisateur($this);
          
             public function getPomodoroSessions(): Collection
    {
        return $this->pomodoroSessions;
    }

    public function addPomodoroSession(PomodoroSession $pomodoroSession): static
    {
        if (!$this->pomodoroSessions->contains($pomodoroSession)) {
            $this->pomodoroSessions->add($pomodoroSession);
            $pomodoroSession->setUser($this);
        

        }

        return $this;
    }


    public function removePomodoroSession(PomodoroSession $pomodoroSession): static
    {
        if ($this->pomodoroSessions->removeElement($pomodoroSession)) {
            // set the owning side to null (unless already changed)
            if ($pomodoroSession->getUser() === $this) {
                $pomodoroSession->setUser(null);
                 }
        }

        return $this;
    }

    public function removeNote(Note $note): static
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getUtilisateur() === $this) {
                $note->setUtilisateur(null);
            }
        }

        return $this;
    }

//     public function __toString(): string
// {
//     return $this->getEmail(); // Ça va forcer Lexik à mettre l'email comme "username" dans le token
// }


}
