<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\GameSessionManagerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class GameController extends AbstractController
{
    public function __construct(private GameSessionManagerService $gameSessionManagerService) {}

    #[Route('/game', name: 'app_game')]
    public function index(): Response
    {

        $selectedCharacter = $this->gameSessionManagerService->getSelectedCharacter();
        if (!$selectedCharacter) throw new \LogicException('User has no selected character.');
        return $this->render('game/index.html.twig', [
            'character' => $selectedCharacter,
        ]);
    }
}
