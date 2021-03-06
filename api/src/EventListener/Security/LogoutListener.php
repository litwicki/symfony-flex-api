<?php

namespace App\EventListener\Security;

use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class LogoutListener implements LogoutSuccessHandlerInterface
{
    private $tokenStorage;

    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     *  On Logout, terminate all Sessions for this User.
     */
    public function onLogoutSuccess(Request $request)
    {
        $response = new RedirectResponse('/');
        $response->headers->clearCookie('api_key');
        return $response;
    }

}