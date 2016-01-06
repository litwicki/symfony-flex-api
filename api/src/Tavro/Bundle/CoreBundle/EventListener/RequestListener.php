<?php

namespace Tavro\Bundle\CoreBundle\EventListener;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Tavro\Bundle\CoreBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\RememberMeToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

use Symfony\Component\HttpFoundation\Cookie;

class RequestListener extends Controller implements ContainerAwareInterface
{
    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernel::MASTER_REQUEST != $event->getRequestType()) {
            // don't do anything if it's not the master request
            return;
        }

        $request = $event->getRequest();
        $cookies = $request->cookies;

        $token = $this->get('security.token_storage')->getToken();

        if($token instanceof UsernamePasswordToken || $token instanceof RememberMeToken) {

            $user = $token->getUser();

            if($cookies->has('api_key')) {

                $api_key = $cookies->get('api_key');

                /**
                 * If the User is logged in and there is a valid api_key cookie, but the
                 * cookie does not match the User's properties, destroy their session,
                 * and the cookie, and force them to reauthenticate immediately.
                 */
                if($user->getApiKey() != $api_key) {

                    $this->get('security.token_storage')->setToken(null);
                    $request->getSession()->invalidate();
                    $response = new RedirectResponse($this->container->get('router')->generate('login'));

                    $response->headers->clearCookie('api_key');
                    $response->headers->clearCookie('api_password');

                    $this->container->get('session')->getFlashBag()->add(
                        'warning',
                        'We are terribly sorry, but your session had become corrupted, so you must reauthenticate.'
                    );

                    $event->setResponse($response);
                    return;

                }

            }
            else {

                /**
                 * If the cookie was somehow reset or deleted manually, we need to reset it!
                 */
                $api_key = $user->getApiKey();
                $response = new RedirectResponse($this->container->get('router')->generate('index'));
                $cookie = new Cookie('api_key', $user->getApiKey(), 0, '/', NULL, false, false);
                $response->headers->setCookie($cookie);
                $event->setResponse($response);
                return;

            }

        }
        else {

            /**
             * If the User has a "stale" API cookie, invalidate it and force them to reauthenticate their session(s)
             * or at the very least clear their old cookies if they're simply an anonymous user now.
             */

            if($cookies->has('api_key') && $token instanceof AnonymousToken) {
                $response = new RedirectResponse('/');
                $response->headers->clearCookie('api_key');
                $response->headers->clearCookie('api_password');
                $event->setResponse($response);
                return;
            }

        }

    }

}