<?php
namespace App\Service;

use App\Entity\Character;
use App\Entity\CharacterTime;
use App\Entity\Tavern;
use App\Entity\User;
use App\Entity\Inventory;
use App\Entity\InventoryOwnership;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class NewPlayerSetupService
{
    private const GAME_START_DATE = '1100-07-06 05:50:00';
    private const TIME_SPEED_MULTIPLIER = 2.0;

    public function __construct(
        private EntityManagerInterface $entityManager
    ){}

    public function setupNewCharacter(User $user, Character $character, string $tavernName = 'Local tavern'): void
    {
        $this->entityManager->beginTransaction();
        try {
            $character->setUser($user);
            $this->entityManager->persist($character);

            $tavern = new Tavern();
            $tavern->setName($tavernName);
            $tavern->setOwner($character);
            $character->setTavern($tavern);
            $this->entityManager->persist($tavern);

            // Flush first to get IDs
            $this->entityManager->flush();

            // Create character's inventory
            $characterInventory = new Inventory(50.0); // Personal inventory has 50kg capacity
            $characterOwnership = new InventoryOwnership();
            $characterOwnership->setOwnerId($character->getId())  // Now we have the ID
                ->setOwnerType('character')
                ->setInventory($characterInventory);
            $characterInventory->setOwnership($characterOwnership);
            $character->setInventory($characterInventory);
            $this->entityManager->persist($characterInventory);
            $this->entityManager->persist($characterOwnership);

            // Create tavern's inventory
            $tavernInventory = new Inventory(500.0); // Tavern inventory has 500kg capacity
            $tavernOwnership = new InventoryOwnership();
            $tavernOwnership->setOwnerId($tavern->getId())  // Now we have the ID
                ->setOwnerType('tavern')
                ->setInventory($tavernInventory);
            $tavernInventory->setOwnership($tavernOwnership);
            $tavern->setInventory($tavernInventory);
            $this->entityManager->persist($tavernInventory);
            $this->entityManager->persist($tavernOwnership);
            
            $characterTime = new CharacterTime();
            $characterTime->setOwningCharacter($character);
            $characterTime->setStartDate(new DateTimeImmutable(self::GAME_START_DATE));
            $characterTime->setCharacterCurrentDate(new DateTime(self::GAME_START_DATE));
            $characterTime->setTimeMultiplier(self::TIME_SPEED_MULTIPLIER);
            $this->entityManager->persist($characterTime);

            // Add initial items to character's inventory
            $characterInventory->addItem('wooden_mug', 1);

            // Add initial items to tavern's inventory
            $tavernInventory->addItem('wooden_mug', 10);
            $tavernInventory->addItem('apple', 20);

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }
}
