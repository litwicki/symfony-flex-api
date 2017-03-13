<?php namespace Litwicki\ApiBundle\EventSubscriber\Exception\Dev;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

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

        $data = [
            'code' => $code,
            'message' => $exception->getMessage(),
            'error' => $exception->getLine(),
            'debug' => $exception->getTraceAsString(),
        ];

        $content = $this->serializer->serialize($data, $format);

        $response = new Response();
        $response->headers->set('Content-Type', sprintf('application/%s', $format));
        $response->setStatusCode($code);
        $response->setContent($content);
        $event->setResponse($response);

    }
}