<?php

namespace App\Entity;

use App\Repository\PomodoroSessionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PomodoroSessionRepository::class)]
class PomodoroSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $pomodoros_completes = null;

    #[ORM\Column(nullable: true)]
    private ?int $pomodoroDuration = null;

    #[ORM\Column(nullable: true)]
    private ?int $shortBreak = null;

    #[ORM\Column(nullable: true)]
    private ?int $longBreak = null;

    #[ORM\Column(nullable: true)]
    private ?bool $autoStart = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'pomodoroSessions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;
    
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeImmutable $startedAt): static
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getEndedAt(): ?\DateTimeImmutable
    {
        return $this->endedAt;
    }

    public function setEndedAt(?\DateTimeImmutable $endedAt): static
    {
        $this->endedAt = $endedAt;

        return $this;
    }

    public function getPomodorosCompletes(): ?int
    {
        return $this->pomodoros_completes;
    }

    public function setPomodorosCompletes(?int $pomodoros_completes): static
    {
        $this->pomodoros_completes = $pomodoros_completes;

        return $this;
    }

    public function getPomodoroDuration(): ?int
    {
        return $this->pomodoroDuration;
    }

    public function setPomodoroDuration(?int $pomodoroDuration): static
    {
        $this->pomodoroDuration = $pomodoroDuration;

        return $this;
    }

    public function getShortBreak(): ?int
    {
        return $this->shortBreak;
    }

    public function setShortBreak(?int $shortBreak): static
    {
        $this->shortBreak = $shortBreak;

        return $this;
    }

    public function getLongBreak(): ?int
    {
        return $this->longBreak;
    }

    public function setLongBreak(?int $longBreak): static
    {
        $this->longBreak = $longBreak;

        return $this;
    }

    public function isAutoStart(): ?bool
    {
        return $this->autoStart;
    }

    public function setAutoStart(?bool $autoStart): static
    {
        $this->autoStart = $autoStart;

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
