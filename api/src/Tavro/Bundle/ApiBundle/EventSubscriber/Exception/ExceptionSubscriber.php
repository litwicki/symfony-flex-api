<?php

namespace Tavro\Bundle\ApiBundle\EventSubscriber\Exception;

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
    protected $debug;
    protected $serializer;
    protected $logger;

    public function __construct($debug, $serializer, $logger)
    {
        $this->debug = $debug;
        $this->serializer = $serializer;
        $this->logger = $logger;
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
            'message' => $message,
        ];

        if($this->debug) {
            $data['debug'] = get_class($exception);
            $data['trace'] = $exception->getTraceAsString();
        }

        $format = preg_match('/\.xml$/', $event->getRequest()->getUri()) ? 'xml' : 'json';

        $response = new Response();
        $response->headers->set('Content-Type', sprintf('application/%s', $format));
        $response->setStatusCode($code);
        $response->setContent($this->serializer->serialize($data, $format));
        $event->setResponse($response);

    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     *
     * @throws \Exception
     */
    public function logException(GetResponseForExceptionEvent $event)
    {
        try {

            $exception = $event->getException();
            $code = $exception->getCode();
            $message = $exception->getMessage();

            switch($code) {

                case 401:
                case 403:
                    $this->logger->info($message);
                    break;
                case 500:
                    $this->logger->critical($message, array(
                        // include extra "context" info in your logs
                        'cause' => get_class($exception),
                    ));
                    break;
                default:
                    $this->logger->error($message);
            }

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     */
    public function notifyException(GetResponseForExceptionEvent $event)
    {
        /**
         * If this is a particularly egregious exception, notify the administration
         * team so they are immediately made aware!
         */

        /**
         * @TODO: the logic for determining what's particularly bad :)
         */
    }
}