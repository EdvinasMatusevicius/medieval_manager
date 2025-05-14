<?php

namespace App\EventListener;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class CharacterRedirectListener
{
    private array $routeRules = [
        [
            'path' => '^/characters',
            'roles' => ['ROLE_USER'],
            'requireCharacter' => false,
            'redirects' => [
                'role' => 'app_login', // if role check fails
                'character' => 'app_game', // if character check fails
            ],
        ],
        [
            'path' => '^/game',
            'roles' => ['ROLE_USER'],
            'requireCharacter' => true,
            'redirects' => [
                'role' => 'app_login',
                'character' => 'app_character_selection',
            ]
        ],
    ];

    public function __construct(
        private Security $security, 
        private RouterInterface $router, 
        private AuthorizationCheckerInterface $authChecker
    ) {}

    #[AsEventListener]
    public function onRequestEvent(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $path = $request->getPathInfo();
        $route = $request->attributes->get('_route');
        $user = $this->security->getUser();
        
        if (!$route || !$path) return;
        foreach ($this->routeRules as $rule) {
            if (!preg_match("#{$rule['path']}#", $path)) {
                continue;
            }
            $requiredRoles = $rule['roles'] ?? [];
            $requireCharacter = $rule['requireCharacter'] ?? false;
            $redirects = $rule['redirects'] ?? [];

            $routeIsPublic = in_array('PUBLIC_ACCESS', $requiredRoles);

            $characterIsSelected = $this->authChecker->isGranted('HAS_SELECTED_CHARACTER');
            
            // CHECKS ROLES
            if (!$routeIsPublic) {
                foreach ($requiredRoles as $role) {
                    if (!$this->security->isGranted($role)) {
                        $routeToRedirectTo = $redirects['role'];
                        $targetAndRedirectRoutesMatch = $routeToRedirectTo === $route;
                        if ($routeToRedirectTo && !$targetAndRedirectRoutesMatch) {
                            $event->setResponse(new RedirectResponse($this->router->generate($routeToRedirectTo)));
                        }
                        return;
                    }
                }
            }
            // CHECKS CHAR SELECTION
            if ($requireCharacter !== $characterIsSelected) {
                $routeToRedirectTo = $redirects['character'];
                $targetAndRedirectRoutesMatch = $routeToRedirectTo === $route;
                if ($routeToRedirectTo && !$targetAndRedirectRoutesMatch)
                    $event->setResponse(new RedirectResponse($this->router->generate($routeToRedirectTo)));
                return;
            }

        }
    }
}
