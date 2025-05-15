<?php

namespace App\Controller;

use App\Entity\Character;
use App\Entity\User;
use App\Form\CharacterTypeForm;
use App\Repository\CharacterRepository;
use App\Service\GameSessionManagerService;
use App\Service\NewPlayerSetupService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/characters', name: '')]
final class CharacterController extends AbstractController
{

    public function __construct(
        private CharacterRepository $characterRepository,
        private NewPlayerSetupService $newPlayerSetupService,
        private GameSessionManagerService $gameSessionManagerService
    ) {}

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
            $this->newPlayerSetupService->setupNewCharacter($user, $character);
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
        // TO DO double check if character is selected just in case and remove
        $this->characterRepository->remove($character);
        
        $this->addFlash('success', 'Character deleted successfully');
        return $this->redirectToRoute('app_character_selection');
    }

    #[Route('/select/{id}', name: 'app_select_character', methods:['GET'])]
    public function select($id) {
        $character = $this->characterRepository->find($id);
        if (!$character) {
            throw $this->createNotFoundException('Character not found');
        }
        /** @var User $user */
        $user = $this->getUser();
        
        if ($user instanceof User) {
            if ($character->getUser() !== $user) {
                throw $this->createAccessDeniedException('You dont own this character');
            }
            $this->gameSessionManagerService->setSelectedCharacter($character);
            return $this->redirectToRoute('app_game');
        } else {
            throw new \LogicException('Authenticated user is not an instance of App\Entity\User.');
        }
    }
}
