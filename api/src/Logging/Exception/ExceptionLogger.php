<?php namespace App\Logging\Exception;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class ExceptionLogger
{
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Log an Exception.
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     */
    public function log(GetResponseForExceptionEvent $event)
    {
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

}