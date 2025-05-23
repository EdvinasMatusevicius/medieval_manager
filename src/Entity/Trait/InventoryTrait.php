<?php

declare(strict_types=1);

namespace App\Entity\Trait;

use App\Entity\Inventory;
use Doctrine\ORM\Mapping as ORM;

trait InventoryTrait
{
    #[ORM\OneToOne(targetEntity: Inventory::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Inventory $inventory = null;

    public function getInventory(): ?Inventory
    {
        return $this->inventory;
    }

    public function setInventory(?Inventory $inventory): self
    {
        $this->inventory = $inventory;
        return $this;
    }
} 