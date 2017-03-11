<?php

namespace Tavro\Bundle\ApiBundle\EventSubscriber\Exception\Dev;

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

class ExceptionSubscriber extends \Tavro\Bundle\ApiBundle\EventSubscriber\Exception\ExceptionSubscriber
{
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

        $message = $exception->getMessage();

        /**
         * For some particular exceptions, we want to give a generic message response.
         */

        if($exception instanceof AuthenticationCredentialsNotFoundException) {
            $message = 'You must be authorized to access this resource.';
            $code = Response::HTTP_UNAUTHORIZED;
        }

        if($exception instanceof AccessDeniedHttpException) {
            $message = 'You do not have permission to access this resource.';
            $code = Response::HTTP_FORBIDDEN;
        }

        if($code === Response::HTTP_NOT_FOUND) {
            $message = 'The resource you were looking for could not be found.';
        }

        if($code === Response::HTTP_INTERNAL_SERVER_ERROR) {
            $message = 'There was an error completing your request.';
        }

        $data = [
            'code' => $code,
            'message' => $message,
        ];

        $content = $this->serializer->serialize($data, $format);

        $response = new Response();
        $response->headers->set('Content-Type', sprintf('application/%s', $format));
        $response->setStatusCode($code);
        $response->setContent($content);
        $event->setResponse($response);

    }
}