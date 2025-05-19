<?php
namespace App\Service;

use App\Entity\Character;
use App\Entity\CharacterTime;
use App\Entity\Tavern;
use App\Entity\User;
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
            
            $characterTime = new CharacterTime();
            $characterTime->setOwningCharacter($character);
            $characterTime->setStartDate(new DateTimeImmutable(self::GAME_START_DATE));
            $characterTime->setCharacterCurrentDate(new DateTime(self::GAME_START_DATE));
            $characterTime->setTimeMultiplier(self::TIME_SPEED_MULTIPLIER);
            $this->entityManager->persist($characterTime);

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }
}
