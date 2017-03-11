<?php

namespace Tavro\Bundle\ApiBundle\EventSubscriber\Exception;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Monolog\Logger;
use Tavro\Bundle\CoreBundle\Logging\Exception\TavroExceptionLogger;
use Tavro\Bundle\CoreBundle\Serializer\Serializer;

class ExceptionSubscriber implements EventSubscriberInterface
{
    protected $debug;
    protected $serializer;
    protected $logger;

    public function __construct($debug, Serializer $serializer, TavroExceptionLogger $logger)
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
        $code = $exception->getCode() == 0 ? Response::HTTP_BAD_REQUEST : $exception->getCode();
        $format = preg_match('/\.xml$/', $event->getRequest()->getUri()) ? 'xml' : 'json';

        $data = [
            'code' => $code,
            'message' => $exception->getMessage()
        ];

        $content = $this->serializer->serialize($data, $format);

        $response = new Response();
        $response->headers->set('Content-Type', sprintf('application/%s', $format));
        $response->setStatusCode($code);
        $response->setContent($content);
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
            $this->logger->log($event->getException());
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