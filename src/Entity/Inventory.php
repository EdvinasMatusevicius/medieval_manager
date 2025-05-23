<?php

declare(strict_types=1);

namespace App\Entity;

use App\Exception\InventoryException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Inventory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private float $maxWeight;

    #[ORM\OneToMany(mappedBy: 'inventory', targetEntity: InventoryItem::class, cascade: ['persist', 'remove'])]
    private Collection $items;

    #[ORM\OneToOne(mappedBy: 'inventory', targetEntity: InventoryOwnership::class, cascade: ['persist', 'remove'])]
    private ?InventoryOwnership $ownership = null;

    public function __construct(float $maxWeight = 100.0)
    {
        $this->maxWeight = $maxWeight;
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaxWeight(): float
    {
        return $this->maxWeight;
    }

    public function setMaxWeight(float $maxWeight): self
    {
        $this->maxWeight = $maxWeight;
        return $this;
    }

    public function getCurrentWeight(): float
    {
        $weight = 0.0;
        foreach ($this->items as $item) {
            $itemConfig = $this->getItemConfig($item->getItemName());
            $weight += $itemConfig['weight'] * $item->getQuantity();
        }
        return $weight;
    }

    public function canAddItem(string $itemName, int $quantity): bool
    {
        $itemConfig = $this->getItemConfig($itemName);
        $newWeight = $this->getCurrentWeight() + ($itemConfig['weight'] * $quantity);
        return $newWeight <= $this->maxWeight;
    }

    public function addItem(string $itemName, int $quantity): self
    {
        if (!$this->canAddItem($itemName, $quantity)) {
            throw new InventoryException("Cannot add items: weight limit would be exceeded");
        }

        $this->getItemConfig($itemName); // Validate item exists

        $existingItem = $this->findItem($itemName);
        if ($existingItem) {
            $existingItem->setQuantity($existingItem->getQuantity() + $quantity);
        } else {
            $item = new InventoryItem();
            $item->setItemName($itemName)
                ->setQuantity($quantity)
                ->setInventory($this);
            $this->items->add($item);
        }

        return $this;
    }

    public function removeItem(string $itemName, int $quantity): self
    {
        $item = $this->findItem($itemName);
        if (!$item) {
            throw new InventoryException("Item not found in inventory");
        }

        if ($item->getQuantity() < $quantity) {
            throw new InventoryException("Not enough items to remove");
        }

        $newQuantity = $item->getQuantity() - $quantity;
        if ($newQuantity === 0) {
            $this->items->removeElement($item);
        } else {
            $item->setQuantity($newQuantity);
        }

        return $this;
    }

    public function transferTo(Inventory $target, string $itemName, int $quantity): self
    {
        if (!$target->canAddItem($itemName, $quantity)) {
            throw new InventoryException("Target inventory cannot accept these items due to weight limit");
        }

        $this->removeItem($itemName, $quantity);
        $target->addItem($itemName, $quantity);

        return $this;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function getOwnership(): ?InventoryOwnership
    {
        return $this->ownership;
    }

    public function setOwnership(?InventoryOwnership $ownership): self
    {
        $this->ownership = $ownership;
        return $this;
    }

    private function findItem(string $itemName): ?InventoryItem
    {
        foreach ($this->items as $item) {
            if ($item->getItemName() === $itemName) {
                return $item;
            }
        }
        return null;
    }

    //TODO: might be better in service, move item data into src for easyer access?
    private function getItemConfig(string $itemName): array
    {
        $items = require dirname(__DIR__, 2) . '/config/game/items.php';
        if (!isset($items[$itemName])) {
            throw new InventoryException("Item '$itemName' does not exist in configuration");
        }
        return $items[$itemName];
    }
} 