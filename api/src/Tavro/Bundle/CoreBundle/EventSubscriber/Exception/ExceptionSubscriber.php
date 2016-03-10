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

    public function processException(GetResponseForExceptionEvent $event)
    {
        return;
        $exception = $event->getException();

        switch($exception->getCode()) {

//            case 0:
//                $message = 'That resource is not available.';
//                break;
            default:
                $message = $exception->getMessage();

        }

        $message = array(
            'code' => $exception->getCode(),
            'message' => $message,
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