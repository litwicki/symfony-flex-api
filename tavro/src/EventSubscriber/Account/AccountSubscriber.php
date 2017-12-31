<?php

namespace App\EventSubscriber\Account;

use Psr\Log\LoggerInterface;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

use App\Event\Account\AccountCreateEvent;
use App\Event\Account\AccountDeactivateEvent;
use App\Event\Account\AccountDeleteEvent;
use App\Event\Account\AccountOwnerDeactivateEvent;
use App\Mail\TavroMailer;

class AccountSubscriber implements EventSubscriberInterface
{
    protected $mailer;
    protected $logger;

    public function __construct(TavroMailer $mailer, LoggerInterface $logger)
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
            AccountCreateEvent::NAME => 'onAccountCreate',
            AccountDeleteEvent::NAME => 'onAccountDelete',
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
     * Send an Email Notification to the Account owner welcoming them.
     * Send an Email Notification to Staff about the new Account.
     *
     * @param \App\Event\Account\AccountCreateEvent $event
     */
    public function onAccountCreate(AccountCreateEvent $event)
    {

        $account = $event->getAccount();

        /**
         * Send Email to Staff internally.
         */
        $this->mailer->send([
            'recipients' => $this->mailer->getAppEmail(),
            'subject' => sprintf('New Account (%s)', $account->__toString()),
            'entity' => $account,
            'type' => 'Account/StaffAccountCreate'
        ]);

        /**
         * Send Email to the Account Owner.
         */
        $this->mailer->send([
            'recipients' => $account->getUser()->getPerson()->getEmail(),
            'subject' => sprintf('New Account (%s)', $account->__toString()),
            'entity' => $account,
            'type' => 'Account/AccountCreate'
        ]);

        $this->logger->info(sprintf(
            'New Account %s signed up by email %s', $account->__toString(), $account->getUser()->getEmail())
        );

    }

    /**
     * When an Account is Deleted..
     *
     * @param \App\Event\Account\AccountDeleteEvent $event
     */
    public function onAccountDelete(AccountDeleteEvent $event)
    {

        $account = $event->getAccount();

        /**
         * Send Email to Staff internally.
         */
        $this->mailer->send([
            'recipients' => $this->mailer->getAppEmail(),
            'subject' => sprintf('New Account (%s)', $account->__toString()),
            'entity' => $account,
            'type' => 'Account/StaffAccountDelete'
        ]);

        /**
         * Send Email to the Account Owner.
         */
        $this->mailer->send([
            'recipients' => $account->getUser()->getPerson()->getEmail(),
            'subject' => sprintf('New Account (%s)', $account->__toString()),
            'entity' => $account,
            'type' => 'Account/AccountDelete'
        ]);

        $this->logger->info(sprintf(
            'Account %s deleted', $account->__toString())
        );

    }

    /**
     * When an Account is Deactivated..
     *
     * @param \App\Event\Account\AccountDeactivateEvent $event
     */
    public function onAccountDeactivate(AccountDeactivateEvent $event)
    {

        $account = $event->getAccount();

        $this->mailer->send([
            'recipients' => $this->mailer->getAppEmail(),
            'subject' => sprintf('Tavro Account %s has been Deactivated', $account->__toString()),
            'entity' => $account,
            'type' => 'Account/AccountDeactivated'
        ]);

        $this->logger->info(sprintf(
            'Account %s deactivated', $account->__toString())
        );

    }



    /**
     * When an Account is Deactivated by its owner..
     *
     * @param \App\Event\Account\AccountOwnerDeactivateEvent $event
     */
    public function onAccountOwnerDeactivate(AccountOwnerDeactivateEvent $event)
    {

        $account = $event->getAccount();

        /**
         * Let the account owner know their account is deactivated
         */
        $this->mailer->send([
            'recipients' => [$this->mailer->getAppEmail(), $account->getUser()->getPerson()->getEmail()],
            'subject' => sprintf('Your Tavro Account was successfully deactivated.'),
            'entity' => $account,
            'type' => 'Account/AccountOwnerDeactivated'
        ]);

        $this->logger->info(sprintf(
            'Account %s deactivated by owner', $account->__toString())
        );

    }


}