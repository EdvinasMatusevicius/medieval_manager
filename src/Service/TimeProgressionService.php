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

    public function updateAllPlayers(int $IRLSecondsPassed)
    {
        $this->entityManager->beginTransaction();
        try {
            
            /** @var Character[] $allCharacters */
            $allCharacters = $this->characterRepository->findAll();
            foreach ($allCharacters as $character) {
                $characterTime = $character->getCharacterTime();
                $timeSpeedMultiplier = $characterTime->getTimeMultiplier();
                $secondsToProgress = (int) floor($timeSpeedMultiplier * $IRLSecondsPassed);
                if (!is_int($secondsToProgress)) continue; //TO DO: HANDLE ERROR
                $currentGameDate = $characterTime->getCharacterCurrentDate();
                $newGameDate = clone $currentGameDate; 
                $newGameDate->modify("+$secondsToProgress seconds");
                $characterTime->setCharacterCurrentDate($newGameDate);
                // $character->addPersonalGold(3);
                $this->entityManager->persist($character);
                $this->entityManager->persist($characterTime);
            }
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }
}