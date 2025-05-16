<?php

namespace App\Service;

use App\Entity\Character;
use App\Entity\User;
use App\Repository\CharacterRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

class GameSessionManagerService
{
    private const SELECTED_CHAR_ID = 'SELECTED_CHAR_ID';

    public function __construct(
        private RequestStack $requestStack,
        private CharacterRepository $characterRepository,
        private Security $security
    ) {}
    
    /**
     * Get the currently selected character
     */
    public function getSelectedCharacter(): ?Character
    {
        /** @var User|null $user */
        $user = $this->security->getUser();
        if (!$user) {
            return null;
        }
        
        $characterId = $this->requestStack->getSession()->get(self::SELECTED_CHAR_ID);
        if (!$characterId) {
            return null;
        }
        
        $character = $this->characterRepository->find($characterId);
        
        // Verify the character belongs to the current user
        if ($character && $user->getCharacters()->contains($character)) {
            return $character;
        }
        
        return null;
    }
    
    /**
     * Set the selected character for the current user
     */
    public function setSelectedCharacter(?Character $character): void
    {
        /** @var User|null $user */
        $user = $this->security->getUser();
        if (!$user) {
            return;
        }
        
        // If character is null, clear the selection
        if ($character === null) {
            $this->requestStack->getSession()->remove(self::SELECTED_CHAR_ID);
            return;
        }

        if (!$user->getCharacters()->contains($character)) {
            throw new \InvalidArgumentException("The selected character does not belong to this user.");
        }

        $this->requestStack->getSession()->set(self::SELECTED_CHAR_ID, $character->getId());
    }
    
    /**
     * Check if the current user has selected a character
     */
    public function hasSelectedCharacter(): bool
    {
        return $this->getSelectedCharacter() !== null;
    }
    
    /**
     * Clear the selected character
     */
    public function clearSelectedCharacter(): void
    {
        $this->requestStack->getSession()->remove(self::SELECTED_CHAR_ID);
    }
}