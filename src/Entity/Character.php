<?php

namespace App\Entity;

use App\Entity\Trait\InventoryTrait;
use App\Repository\CharacterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterRepository::class)]
#[ORM\Table(name: '`character`')]
class Character
{
    use InventoryTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'characters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToOne(mappedBy: 'owner', cascade: ['persist', 'remove'])]
    private ?Tavern $tavern = null;

    #[ORM\Column(type: 'integer')]
    private int $personalGold = 0;

    #[ORM\OneToOne(mappedBy: 'owningCharacter', cascade: ['persist', 'remove'])]
    private ?CharacterTime $characterTime = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getTavern(): ?Tavern
    {
        return $this->tavern;
    }

    public function setTavern(Tavern $tavern): static
    {
        // set the owning side of the relation if necessary
        if ($tavern->getOwner() !== $this) {
            $tavern->setOwner($this);
        }

        $this->tavern = $tavern;

        return $this;
    }

    public function getPersonalGold(): ?int
    {
        return $this->personalGold;
    }

    public function addPersonalGold(int $amount): static
    {
        if ($amount < 0) throw new \InvalidArgumentException('Cannot add negative amount of gold');
        $this->personalGold += $amount;

        return $this;
    }

    public function removePersonalGold(int $amount): static
    {
        if ($amount < 0) throw new \InvalidArgumentException('Cannot remove negative amount of gold');
        if ($this->personalGold < $amount) throw new \DomainException('Not enough gold to remove');

        $this->personalGold -= $amount;

        return $this;
    }

    public function getCharacterTime(): ?CharacterTime
    {
        return $this->characterTime;
    }

    public function setCharacterTime(CharacterTime $characterTime): static
    {
        // set the owning side of the relation if necessary
        if ($characterTime->getOwningCharacter() !== $this) {
            $characterTime->setOwningCharacter($this);
        }

        $this->characterTime = $characterTime;

        return $this;
    }
}
