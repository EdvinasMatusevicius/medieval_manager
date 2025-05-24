<?php

namespace App\Entity;

use App\Entity\Trait\InventoryTrait;
use App\Repository\TavernRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TavernRepository::class)]
class Tavern
{
    use InventoryTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToOne(inversedBy: 'tavern', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Character $owner = null;

    #[ORM\OneToOne(
        mappedBy: 'tavern',
        targetEntity: InventoryOwnership::class,
        fetch: 'LAZY',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private ?InventoryOwnership $inventoryOwnership = null;

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

    public function getOwner(): ?Character
    {
        return $this->owner;
    }

    public function setOwner(Character $owner): static
    {
        $this->owner = $owner;
        return $this;
    }

    protected function getInventoryOwnershipLink(): ?InventoryOwnership
    {
        return $this->inventoryOwnership;
    }

    public function setInventoryOwnership(?InventoryOwnership $ownership): self
    {
        if ($this->inventoryOwnership !== $ownership) {
            $this->inventoryOwnership = $ownership;
            if ($ownership !== null && $ownership->getTavern() !== $this) {
                $ownership->setTavern($this);
            }
            $this->invalidateInventoryCache();
        }
        return $this;
    }
}
