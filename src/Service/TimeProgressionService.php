<?php

namespace App\Service;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use Doctrine\ORM\EntityManagerInterface;

class TimeProgressionService {

    public function __construct(
        private CharacterRepository $characterRepository,
        private EntityManagerInterface $entityManager
    ) {}

    public function updateAllPlayers()
    {
        $this->entityManager->beginTransaction();

        
        try {
            
            /** @var Character[] $allCharacters */
            $allCharacters = $this->characterRepository->findAll();
            foreach ($allCharacters as $character) {
                $character->addPersonalGold(3);
                $this->entityManager->persist($character);
            }
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }
}