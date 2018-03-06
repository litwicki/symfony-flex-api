<?php

namespace App\Notifications;

use App\Model\HandlerInterface\EntityHandlerInterface;
use Aws\Sns\SnsClient;
use Cocur\Slugify\Slugify;

class NotificationHandler implements EntityHandlerInterface
{
    protected $client;
    protected $logger;

    /**
     * NotificationHandler constructor.
     *
     * @param \Aws\Sns\SnsClient $client
     * @param $logger
     */
    public function __construct(SnsClient $client, $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * Push a Notification.
     *
     * @param $subject
     * @param $message
     */
    public function send($subject, $message)
    {
        $slugify = new Slugify();
        $slug = $slugify->slugify($subject);

        $response = $this->client->publish($slug, $message, array(
            'Subject' => $subject
        ));

        if ($response->isOK()) {
            $this->logger->info(sprintf(
                'Notification Pushed: %s', $message
            ));
        }
        else {
            $this->logger->error(sprintf(
                'Error Pushing Notification: %s', $message
            ));
        }
    }

    public function get($id)
    {
        //@TODO:
    }

}