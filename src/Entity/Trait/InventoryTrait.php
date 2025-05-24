<?php

declare(strict_types=1);

namespace App\Entity\Trait;

use App\Entity\Inventory;
use App\Entity\InventoryOwnership;

trait InventoryTrait
{
    /**
     * Internal cache for the loaded Inventory instance.
     * Prefixed with _trait_ to avoid potential name collisions with entity properties.
     */
    private ?Inventory $_trait_inventory_cache = null;

    /**
     * Flag to track if the inventory has been loaded or an attempt was made.
     */
    private bool $_trait_inventory_loaded = false;

    /**
     * Retrieves the Inventory associated with this entity.
     *
     * This method relies on the entity using this trait to implement
     * the abstract method `getInventoryOwnershipLink()`, which should return
     * the InventoryOwnership object linked to this entity.
     *
     * Doctrine's lazy loading will handle fetching the InventoryOwnership
     * and then the Inventory when first accessed.
     *
     * @return Inventory|null The associated Inventory, or null if none.
     */
    public function getInventory(): ?Inventory
    {
        if (!$this->_trait_inventory_loaded) {
            // Call the abstract method which must be implemented by the entity using this trait.
            // This method is expected to return the InventoryOwnership entity.
            $ownershipRelation = $this->getInventoryOwnershipLink();

            if ($ownershipRelation instanceof InventoryOwnership) {
                $this->_trait_inventory_cache = $ownershipRelation->getInventory();
            } else {
                $this->_trait_inventory_cache = null;
            }
            $this->_trait_inventory_loaded = true;
        }
        return $this->_trait_inventory_cache;
    }

    /**
     * Abstract method to be implemented by any entity using this InventoryTrait.
     *
     * This method should return the `InventoryOwnership` entity that is specifically
     * linked to the instance of the entity using the trait. This is typically achieved
     * by defining a OneToOne relationship in the entity (e.g., Character) that is
     * `mappedBy` a field in the `InventoryOwnership` entity.
     *
     * @return InventoryOwnership|null The linked InventoryOwnership object, or null if none.
     */
    abstract protected function getInventoryOwnershipLink(): ?InventoryOwnership;

    /**
     * Allows direct setting of the inventory cache.
     * Useful for testing or if an InventoryLoader service (e.g., a Doctrine listener)
     * were to populate it. This method does NOT persist any changes to the database
     * regarding the inventory ownership.
     *
     * @param Inventory|null $inventory The inventory to set in the cache.
     * @return self
     */
    public function setCachedInventory(?Inventory $inventory): self
    {
        $this->_trait_inventory_cache = $inventory;
        // If explicitly set, we can consider it "loaded" for caching purposes.
        $this->_trait_inventory_loaded = true;
        return $this;
    }

    /**
     * Call this method if the underlying InventoryOwnershipLink changes
     * to ensure getInventory() re-fetches the correct data.
     */
    protected function invalidateInventoryCache(): void
    {
        $this->_trait_inventory_cache = null;
        $this->_trait_inventory_loaded = false;
    }
} 