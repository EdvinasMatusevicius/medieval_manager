<?php

namespace App\Controller;

use App\Entity\Character;
use App\Form\CharacterTypeForm;
use App\Repository\CharacterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/characters', name: '')]
final class CharacterController extends AbstractController
{

    public function __construct(private CharacterRepository $characterRepository)
    {
    }

    #[Route('/', name: 'app_character_selection', methods:['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();
        if (!$user) return $this->redirectToRoute('app_login');

        $characters = $this->characterRepository->findBy(['user' => $user]);

        return $this->render('character/select.html.twig', [
            'characters' => $characters,
        ]);
    }
    #[Route('/new', name: 'app_character_new')]
    public function new(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) return $this->redirectToRoute('app_login');
        $character = new Character();

        $form = $this->createForm(CharacterTypeForm::class, $character);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $character->setUser($user);
            $this->characterRepository->save($character, true);
            return $this->redirectToRoute('app_character_selection');
        }

        return $this->render('character/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_character_delete', methods:['DELETE'])]
    public function delete($id) {
        $character = $this->characterRepository->find($id);
        if (!$character) {
            throw $this->createNotFoundException('Character not found');
        }
        
        if ($character->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You cannot delete this character');
        }
        
        $this->characterRepository->remove($character);
        
        $this->addFlash('success', 'Character deleted successfully');
        return $this->redirectToRoute('app_character_selection');
    }
}
