<?php

namespace Tavro\Bundle\CoreBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ExceptionListener implements ContainerAwareInterface
{
    private $twig;
    private $environment;

    public function __construct($twig, $env)
    {
        $this->twig = $twig;
        $this->environment = $env;
    }

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = NULL)
    {
        $this->container = $container;
    }

//    public function onKernelException(GetResponseForExceptionEvent $event)
//    {
//        // You get the exception object from the received event
//        $exception = $event->getException();
//
//        // HttpExceptionInterface is a special type of exception that
//        // holds status code and header details
//        if ($exception instanceof HttpExceptionInterface) {
//            $statusCode = $exception->getStatusCode();
//        }
//        else {
//            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
//        }
//
//        $request = $event->getRequest();
//        $accept = AcceptHeader::fromString($request->headers->get('Accept'));
//
//        /**
//         * @TODO: Log Exceptions here..
//         */
//
//
//
//    }


    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getException();

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
        }
        else {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        $request = $event->getRequest();
        $accept = AcceptHeader::fromString($request->headers->get('Accept'));

        /**
         *  If the original request is accepting application/json skip this...
         */
        if ($accept->has('application/json') || strpos('curl', $request->headers->get('user-agent'))) {
            return;
        }

        switch ($statusCode) {
            case '401':
            case '403':
            case '404':
            case '405':
            case '406':
                $filename = 'TavroCoreBundle:Error:general.html.twig';
                break;
            default:
                $filename = 'TavroCoreBundle:Error:exception.html.twig';
                break;
        }

        $page = array(
            'exception' => array(
                'trace' => $exception->getTraceAsString(),
                'message' => $exception->getMessage(),
                'statusCode' => $statusCode,
                'line' => $exception->getLine()
            )
        );

        $response = new Response($this->twig->render($filename, $page));

        $event->setResponse($response);

    }

}