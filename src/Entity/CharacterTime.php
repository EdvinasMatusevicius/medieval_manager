<?php

namespace App\Entity;

use App\Repository\CharacterTimeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: CharacterTimeRepository::class)]
#[Broadcast]
class CharacterTime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'characterTime', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Character $owningCharacter = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startDate = null;

    #[ORM\Column]
    private ?\DateTime $characterCurrentDate = null;

    #[ORM\Column]
    private ?float $timeMultiplier = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwningCharacter(): ?Character
    {
        return $this->owningCharacter;
    }

    public function setOwningCharacter(Character $owningCharacter): static
    {
        $this->owningCharacter = $owningCharacter;

        return $this;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeImmutable $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getCharacterCurrentDate(): ?\DateTime
    {
        return $this->characterCurrentDate;
    }

    public function setCharacterCurrentDate(\DateTime $currentDate): static
    {
        $this->characterCurrentDate = $currentDate;

        return $this;
    }

    public function getTimeMultiplier(): ?float
    {
        return $this->timeMultiplier;
    }

    public function setTimeMultiplier(float $timeMultiplier): static
    {
        $this->timeMultiplier = $timeMultiplier;

        return $this;
    }
}
