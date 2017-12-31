<?php

namespace App\EventSubscriber\Syndicate;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

use App\Mail\TavroMailer;
use App\Event\Syndicate\SyndicateCreateEvent;
use App\Event\Syndicate\SyndicateDeleteEvent;

class SyndicateSubscriber implements EventSubscriberInterface
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
            SyndicateCreateEvent::NAME => 'onSyndicateCreate',
            SyndicateDeleteEvent::NAME => 'onSyndicateDelete',
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
     * Send an Email Notification to the Syndicate owner welcoming them.
     * Send an Email Notification to Staff about the new Syndicate.
     *
     * @param \App\Event\Syndicate\SyndicateCreateEvent $event
     */
    public function onSyndicateCreate(SyndicateCreateEvent $event)
    {

        $syndicate = $event->getSyndicate();

        /**
         * Send Email to Staff internally.
         */
        $this->mailer->send([
            'recipients' => $this->mailer->getAppEmail,
            'subject' => sprintf('New Syndicate (%s)', $syndicate->__toString()),
            'entity' => $syndicate,
            'type' => 'Syndicate/StaffSyndicateCreate'
        ]);

        /**
         * Send Email to the Syndicate Owner.
         */
        $this->mailer->send([
            'recipients' => $syndicate->getUser()->getEmail(),
            'subject' => sprintf('New Syndicate (%s)', $syndicate->__toString()),
            'entity' => $syndicate,
            'type' => 'Syndicate/SyndicateCreate'
        ]);

        $this->logger->info(sprintf(
            'New Syndicate %s signed up by email %s', $syndicate->__toString(), $syndicate->getUser()->getEmail())
        );

    }

    /**
     * When an Syndicate is Deleted..
     *
     * @param \App\Event\Syndicate\SyndicateCreateEvent $event
     */
    public function onSyndicateDelete(SyndicateCreateEvent $event)
    {

        $syndicate = $event->getSyndicate();

        /**
         * Send Email to Staff internally.
         */
        $this->mailer->send([
            'recipients' => $this->mailer->getAppEmail,
            'subject' => sprintf('New Syndicate (%s)', $syndicate->__toString()),
            'entity' => $syndicate,
            'type' => 'Syndicate/StaffSyndicateDelete'
        ]);

        /**
         * Send Email to the Syndicate Owner.
         */
        $this->mailer->send([
            'recipients' => $syndicate->getUser()->getEmail(),
            'subject' => sprintf('New Syndicate (%s)', $syndicate->__toString()),
            'entity' => $syndicate,
            'type' => 'Syndicate/SyndicateDelete'
        ]);

        $this->logger->info(sprintf(
            'Syndicate %s deleted', $syndicate->__toString())
        );

    }
}