<?php

namespace Tavro\Bundle\ApiBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiExceptionListener
{
    private $env;

    public function __construct($env)
    {
        $this->env = $env;
    }

    /**
     * Respond with a properly formatted Exception when requesting via the API.
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getException();

        /**
         * @TODO: Handle Api Exception classes uniquely?
         */

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

        $data = array(
            'code'    => $statusCode,
            'message' => str_replace('"', "'", $exception->getMessage()),
        );

        if($this->env != 'prod') {
            $data['debug'] = $exception->getTrace();
        }

        if( $request->isXmlHttpRequest() || $accept->has('application/json') ) {

            if( $accept->has('application/json') ) {
                $response = new Response(json_encode(array('exception' => $data), JSON_FORCE_OBJECT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
                $response->headers->set('Content-Type', 'application/json; charset=utf-8');
                $event->setResponse($response);
                return;
            }
            else {
                $xml = new \SimpleXMLElement('<exception/>');
                $data = array_flip($data);
                array_walk_recursive($data, array ($xml, 'addChild'));
                $response = new Response($xml->asXML());
                $response->headers->set('Content-Type', 'text/xml; charset=utf-8');
                $event->setResponse($response);
                return;
            }

        }

    }
}