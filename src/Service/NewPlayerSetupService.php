<?php
namespace App\Service;

use App\Entity\Character;
use App\Entity\Tavern;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class NewPlayerSetupService
{
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
            

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }
}
