<?php

namespace Tavro\Bundle\CoreBundle\EventListener;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernel;

use Tavro\Bundle\CoreBundle\Entity\User;

class UserActivityListener
{

    protected $tokenStorage;

    protected $em;

    public function __construct(TokenStorage $tokenStorage, EntityManager $em)
    {
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     *
     * @throws \Exception
     */
    public function onCoreController(FilterControllerEvent $event)
    {

        //        Here we are checking that the current request is a "MASTER_REQUEST",
        //        and ignore any subrequest in the process (for example when doing a render() in a twig template)
        if ($event->getRequestType() !== HttpKernel::MASTER_REQUEST) {
            return;
        }

        try {
            //$request = $event->getRequest();

            /**
             *  Validate the User via the Security Context
             */

            $token = $this->tokenStorage->getToken();

            if(is_null($token)) {
                return;
            }
            else {
                $user = $token->getUser();
            }

            $minutes = 0;

            if ($user instanceof User) {

                $lastOnlineDate = $user->getLastOnlineDate();
                $now = new \DateTime();

                if(is_object($lastOnlineDate)) {
                    $diff = $lastOnlineDate->diff($now);
                    $minutes = ($diff->days * 24 * 60);
                }

                if (!is_object($lastOnlineDate) || $minutes > 3) {

                    try {
                        $user->setLastOnlineDate($now);
                        $this->em->persist($user);
                        $this->em->flush();
                    }
                    catch (\Exception $e) {
                        throw new \Exception($e->getMessage());
                    }

                }
            }
        }
        catch(\Exception $e) {
            /**
             * @TODO: something here..
             */
        }

    }
}
