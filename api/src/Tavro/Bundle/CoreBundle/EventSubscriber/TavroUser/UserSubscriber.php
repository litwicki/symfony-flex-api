<?php

namespace Tavro\Bundle\CoreBundle\EventSubscriber\TavroUser;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Tavro\Bundle\CoreBundle\Event\TavroUser\UserSignupEvent;

class UserSubscriber implements EventSubscriberInterface
{
    protected $mailer;

    public function __construct($mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::RESPONSE => array(
                array('onKernelResponsePre', 10),
                array('onKernelResponsePost', -10),
            ),
            UserSignupEvent::NAME => 'onUserSignup',
        );
    }

    public function onKernelResponsePre(FilterResponseEvent $event)
    {
        // ...
    }

    public function onKernelResponsePost(FilterResponseEvent $event)
    {
        // ...
    }

    public function onUserSignup(UserSignupEvent $event)
    {
        $user = $event->getUser();
        $this->mailer->sendActivation($user);
    }
}