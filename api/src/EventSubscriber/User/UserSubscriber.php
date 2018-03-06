<?php

namespace App\EventSubscriber\User;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use App\Event\User\UserActivatedEvent;
use App\Event\User\UserForgotPasswordEvent;
use App\Event\User\UserPasswordChangeEvent;
use App\Event\User\UserResetPasswordEvent;
use App\Event\User\UserSignupEvent;
use App\Mail\AppMailer;

class UserSubscriber implements EventSubscriberInterface
{
    protected $mailer;
    protected $logger;

    public function __construct(AppMailer $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::RESPONSE => array(
                array('onKernelResponsePre', 10),
                array('onKernelResponsePost', -10),
            ),
            UserSignupEvent::NAME => 'onUserSignup',
            UserPasswordChangeEvent::NAME => 'onPasswordChange',
            UserForgotPasswordEvent::NAME => 'onForgotPassword',
            UserActivatedEvent::NAME => 'onUserActivation',
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

    /**
     * Send an Email Notification to the User to activate their email
     * And Log this event to our logging services.
     *
     * @param \App\Event\User\UserSignupEvent $event
     */
    public function onUserSignup(UserSignupEvent $event)
    {

        $user = $event->getUser();

        $this->mailer->send([
            'type' => 'User/activation',
            'user' => $user,
            'recipients' => [$user->getPerson()->getEmail()]
        ]);

        $this->logger->info(sprintf(
            'New User #%s signed up with email %s', $user->getId(), $user->getPerson()->getEmail())
        );

    }

    /**
     * Send an Email Notification to the User to activate their email
     * And Log this event to our logging services.
     *
     * @param \App\Event\User\UserSignupEvent $event
     */
    public function onPasswordChange(UserSignupEvent $event)
    {

        $user = $event->getUser();

        $this->mailer->send([
            'type' => 'User/password-changed',
            'user' => $user,
            'recipients' => [$user->getPerson()->getEmail()]
        ]);

        $this->logger->info(sprintf(
            'User %s changed their password', $user->__toString())
        );

    }

    /**
     * When a User has forgotten their password, send a link via email for them
     * to properly reset their password after clicking said link..
     *
     * @param \App\Event\User\UserForgotPasswordEvent $event
     */
    public function onForgotPassword(UserForgotPasswordEvent $event)
    {

        $user = $event->getUser();

        $this->mailer->send([
            'type' => 'User/forgot-password',
            'user' => $user,
            'recipients' => [$user->getPerson()->getEmail()]
        ]);

        $this->logger->info(sprintf(
            'User %s requested a password change', $user->__toString())
        );

    }

    /**
     * User has activated, send an email with an update and confirmation.
     *
     * @param \App\Event\User\UserActivatedEvent $event
     */
    public function onUserActivation(UserActivatedEvent $event)
    {

        $user = $event->getUser();

        $this->mailer->send([
            'type' => 'User/activated',
            'user' => $user,
            'recipients' => [$user->getPerson()->getEmail()]
        ]);

        $this->logger->info(sprintf(
            'User %s has been activated.', $user->__toString())
        );

    }
}