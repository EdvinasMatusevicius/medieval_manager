<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CharacterSelectionController extends AbstractController
{
    #[Route('/character/selection', name: 'app_character_selection')]
    public function index(): Response
    {
        return $this->render('character_selection/index.html.twig', [
            'controller_name' => 'CharacterSelectionController',
        ]);
    }
}
