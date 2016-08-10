<?php

namespace Tavro\Bundle\CoreBundle\EventSubscriber\Exception;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionSubscriber implements EventSubscriberInterface
{
    protected $env;

    public function __construct($env)
    {
        $this->env = $env;
    }

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
        $code = $exception->getCode() == 0 ? 500 : $exception->getCode();

        $message = $exception->getMessage();

        if($exception instanceof AuthenticationCredentialsNotFoundException) {
            $message = 'You must be authorized to access this resource.';
            $code = 401;
        }

        if($exception instanceof NotFoundHttpException) {
            $message = 'The resource you were looking for could not be found in the great abyss.';
            $code = 404;
        }

        $data = [
            'code' => $code,
            'class' => get_class($exception),
            'message' => $message,
            'trace' => $exception->getTraceAsString(),
        ];

        $response = new JsonResponse();
        $response->setStatusCode($code);
        $response->setData($data);
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