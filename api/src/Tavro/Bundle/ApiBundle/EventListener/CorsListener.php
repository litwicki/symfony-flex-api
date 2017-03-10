<?php namespace Tavro\Bundle\ApiBundle\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class CorsListener
{

    public function onKernelRequest(GetResponseEvent $event)
    {
        // Don't do anything if it's not the master request.
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $request = $event->getRequest();
        $method  = $request->getRealMethod();

        // perform preflight checks
        if ('OPTIONS' === $request->getMethod()) {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization');
            $response->headers->set('Access-Control-Max-Age', 3600);
            //$response->headers->set('Access-Control-Allow-Origin', '*');
            $event->setResponse($response);
            return;
        }
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();

//        $response = $event->getResponse();
//        $response->headers->set('Access-Control-Allow-Credentials', 'true');
//        $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization');
//        $response->headers->set('Access-Control-Allow-Origin', $request->headers->get('origin'));
//        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
//        $response->headers->set('Vary', 'Origin');
//        $event->setResponse($response);

        return;
    }
}