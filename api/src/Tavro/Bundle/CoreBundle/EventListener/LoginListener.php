<?php

namespace Tavro\Bundle\CoreBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * Custom login listener.
 */
class LoginListener implements AuthenticationSuccessHandlerInterface
{
    private $auth;
    private $router;

    public function __construct($auth, $router)
    {
        $this->auth = $auth;
        $this->router = $router;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        if ($this->auth->isGranted('ROLE_USER')) {

            $user = $token->getUser();

            $url = $this->router->generate('index');
            $response = new RedirectResponse($url);

            $cookie = new Cookie('api_key', $user->getApiKey(), 0, '/', NULL, false, false);
            $response->headers->setCookie($cookie);

            if($user->getApiEnabled()) {
                $cookie = new Cookie('api_password', $user->getApiPassword(), 0, '/', NULL, false, false);
                $response->headers->setCookie($cookie);
            }

            unset($cookie);

            return $response;

        }
    }
}