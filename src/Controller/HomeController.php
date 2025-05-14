<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->render('home/index.html.twig');
        } else {
            return $this->render('home/index.html.twig');
            // return $this->redirectToRoute('app_game');
        }
    }
}
