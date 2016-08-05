<?php

namespace Tavro\Bundle\CoreBundle\EventSubscriber\Exception;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return array(
           'kernel.exception' => array(
               array('processException', 10),
               array('logException', 0),
               array('notifyException', -10),
           )
        );
    }

    /**
     * Process an Exception message.
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     */
    public function processException(GetResponseForExceptionEvent $event)
    {

        $exception = $event->getException();

        /**
         * If the Exception code is 0 then we need to be notified
         * because something in the Matrix has failed and we need
         * Agent Smith to come fix it..
         */
        if($exception->getCode() === 0) {

            //log the error code here so we can fix it!

        }

        $message = array(
            'code' => $exception->getCode(),
            'class' => get_class($exception),
            'message' => $exception->getMessage(),
        );

        $message = json_encode($message);
        $response = new Response($message);
        $event->setResponse($response);

    }

    public function logException(GetResponseForExceptionEvent $event)
    {
        // ...
    }

    public function notifyException(GetResponseForExceptionEvent $event)
    {
        // ...
    }
}