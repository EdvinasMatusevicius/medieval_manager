<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\GameSessionManagerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/game', name: '')]
final class GameController extends AbstractController
{
    public function __construct(private GameSessionManagerService $gameSessionManagerService) {}

    #[Route('/', name: 'app_game')]
    public function index(): Response
    {

        $selectedCharacter = $this->gameSessionManagerService->getSelectedCharacter();
        if (!$selectedCharacter) throw new \LogicException('User has no selected character.');
        // $inventory = $selectedCharacter->getInventory();
        // $inventoryItem = $inventory->getItems()->first();
        // var_dump($inventoryItem->getItemName());
        return $this->render('game/index.html.twig', [
            'character' => $selectedCharacter,
        ]);
    }

    /**
     * clears currently selected character and returns to character selecteion screen
     */
    #[Route('/end-session', name: 'app_end_game_session', methods:['GET'])]
    public function endSession(): RedirectResponse
    {
        $this->gameSessionManagerService->clearSelectedCharacter();
        return $this->redirectToRoute('app_character_selection');
    }
}
