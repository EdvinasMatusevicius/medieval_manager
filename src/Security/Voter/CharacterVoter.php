<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class CharacterVoter extends Voter
{
    public const HAS_SELECTED_CHARACTER = 'HAS_SELECTED_CHARACTER';

    public function __construct(private UrlGeneratorInterface $urlGenerator) {}
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::HAS_SELECTED_CHARACTER]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }
        $selectedCharacter = $user->getSelectedCharacter();
        return $selectedCharacter !== null;

        return false;
    }
}
