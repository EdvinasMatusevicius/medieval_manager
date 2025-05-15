<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Service\GameSessionManagerService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class CharacterVoter extends Voter
{
    public const HAS_SELECTED_CHARACTER = 'HAS_SELECTED_CHARACTER';

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private GameSessionManagerService $gameSessionManagerService
    ) {}
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::HAS_SELECTED_CHARACTER]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $selectedCharacter = $this->gameSessionManagerService->getSelectedCharacter();;
        return $selectedCharacter !== null;

        return false;
    }
}
