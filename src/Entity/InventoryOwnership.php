<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class InventoryOwnership
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: Inventory::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Inventory $inventory;

    #[ORM\OneToOne(inversedBy: 'inventoryOwnership', targetEntity: Character::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Character $character = null;

    #[ORM\OneToOne(inversedBy: 'inventoryOwnership', targetEntity: Tavern::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Tavern $tavern = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInventory(): Inventory
    {
        return $this->inventory;
    }

    public function setInventory(Inventory $inventory): self
    {
        $this->inventory = $inventory;
        return $this;
    }

    public function getCharacter(): ?Character
    {
        return $this->character;
    }

    public function setCharacter(?Character $character): self
    {
        $this->character = $character;
        return $this;
    }

    public function getTavern(): ?Tavern
    {
        return $this->tavern;
    }

    public function setTavern(?Tavern $tavern): self
    {
        $this->tavern = $tavern;
        return $this;
    }

    public function getOwner(): object|null
    {
        return $this->character ?? $this->tavern ?? null;
    }
} 