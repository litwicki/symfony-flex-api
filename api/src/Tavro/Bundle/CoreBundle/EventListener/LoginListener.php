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
    private $em;

    public function __construct($auth, $router, $em)
    {
        $this->auth = $auth;
        $this->router = $router;
        $this->em = $em;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        if($this->auth->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            die(__METHOD__);
        }

        if ($this->auth->isGranted('ROLE_USER')) {

            $user = $token->getUser();

            $user->setUserIp($request->getClientIp());
            $user->setUserAgent($request->headers->get('User-Agent'));
            $this->em->persist($user);
            $this->em->flush();

            $url = $this->router->generate('index');
            $response = new RedirectResponse($url);

            $cookie = new Cookie('api_key', $user->getApiKey(), 0, '/', NULL, false, false);
            $response->headers->setCookie($cookie);

            unset($cookie);

            return $response;

        }
    }
}