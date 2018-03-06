<?php namespace App\EventSubscriber\Exception;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use App\Logging\Exception\TavroExceptionLogger;
use App\Serializer\Serializer;

class ExceptionSubscriber implements EventSubscriberInterface
{
    protected $serializer;
    protected $logger;
    private $debug;

    public function __construct(Serializer $serializer, TavroExceptionLogger $logger, $debug)
    {
        $this->serializer = $serializer;
        $this->logger = $logger;
        $this->debug = $debug;
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
     * @throws \Exception
     */
    public function processException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $code = $exception->getCode() == 0 ? Response::HTTP_BAD_REQUEST : $exception->getCode();
        $format = preg_match('/\.xml$/', $event->getRequest()->getUri()) ? 'xml' : 'json';

        $message = $exception->getMessage();
        $regex = '/^Tavro.*Entity[\\\\](.*)/';
        if(preg_match($regex, $message)) {
            $message = preg_replace($regex, '$1', $message);
        }

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

        $data = $this->formatExceptionResponse($code, $message, $exception, $this->debug);

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

    /**
     * @param $code
     * @param $message
     * @param $exception
     *
     * @return array
     */
    public function formatExceptionResponse($code, $message, $exception, $debug = false)
    {
        $message = [
            'code' => $code,
            'message' => $message
        ];

        if($debug) {
            array_merge($message, [
                'error' => sprintf('%s:%s', $exception->getFile(), $exception->getLine()),
                'debug' => $exception->getTraceAsString()
            ]);
        }

        return $message;
    }
}