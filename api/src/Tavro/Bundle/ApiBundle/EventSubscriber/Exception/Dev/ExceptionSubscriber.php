<?php namespace Tavro\Bundle\ApiBundle\EventSubscriber\Exception\Dev;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class ExceptionSubscriber extends \Tavro\Bundle\ApiBundle\EventSubscriber\Exception\ExceptionSubscriber
{
    /**
     * @param $code
     * @param $message
     * @param $exception
     *
     * @return array
     */
    public function formatExceptionResponse($code, $message, $exception)
    {
        return [
            'code' => $code,
            'message' => $exception->getMessage(),
            'error' => sprintf('%s:%s', $exception->getFile(), $exception->getLine()),
            'debug' => $exception->getTraceAsString(),
        ];
    }
}