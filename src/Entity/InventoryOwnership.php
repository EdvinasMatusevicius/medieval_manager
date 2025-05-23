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

    #[ORM\Column]
    private int $ownerId;

    #[ORM\Column(length: 255)]
    private string $ownerType;

    #[ORM\OneToOne(inversedBy: 'ownership', targetEntity: Inventory::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Inventory $inventory;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwnerId(): int
    {
        return $this->ownerId;
    }

    public function setOwnerId(int $ownerId): self
    {
        $this->ownerId = $ownerId;
        return $this;
    }

    public function getOwnerType(): string
    {
        return $this->ownerType;
    }

    public function setOwnerType(string $ownerType): self
    {
        $this->ownerType = $ownerType;
        return $this;
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
} 