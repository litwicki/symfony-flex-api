<?php

namespace Tavro\Bundle\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Tavro\Bundle\ApiBundle\Exception\ApiInvalidPayloadException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiNotFoundException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiRequestLimitException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;

use Doctrine\Common\Inflector\Inflector;

use Litwicki\Common\Common;
use Tavro\Bundle\CoreBundle\Model\HandlerInterface\EntityHandlerInterface;

class DefaultController extends Controller
{

    /**
     * Handle the payload from the Request and do some checks.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function getPayload(Request $request)
    {
        $data = json_decode($request->getContent(), TRUE);

        if(false === (is_array($data))) {
            throw new ApiInvalidPayloadException('Invalid or missing payload in submission.');
        }

    }

    /**
     * Serialize an Entity or array of Entities.
     *
     * @param $data
     * @param string $format
     * @param string $group
     *
     * @return
     * @throws \Exception
     * @internal param $string $group
     *
     */
    public function serialize($data, $format = 'json', $group = 'api')
    {
        try {
            $serializer = $this->get('tavro_serializer');
            return $serializer->serialize($data, $format, $group);
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Fetch the proper handler for an entity by its route name.
     *
     * @param $entityName
     *
     * @return object
     * @throws \Exception
     */
    public function getHandler($entityName)
    {

        $handler = $this->get(sprintf('tavro.handler.%s', $entityName));

        if(false === ($handler instanceof EntityHandlerInterface)) {
            throw new ApiException(sprintf('Could not find handler for %s', $entityName));
        }

        return $handler;

    }

    /**
     * @param $raw
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function apiResponse($raw, array $options = array())
    {

        $format = isset($options['format']) ? $options['format'] : 'json';
        $group = isset($options['group']) ? $options['group'] : 'api';
        $code = isset($options['code']) ? $options['code'] : Response::HTTP_OK;
        $message = isset($options['message']) ? $options['message'] : '';

        if($format == 'json') {
            return $this->jsonResponse($raw, $message, $code, $group);
        }
        else {
            $response = new Response();
            $response->headers->set('Content-Type', 'application/xml');
            $responseData = [];
            $response->setContent($responseData);
            $response->setStatusCode($code);
        }

        return $response;
    }

    /**
     * Return a JSON Response.
     * 
     * @param $data
     * @param $message
     * @param $code
     * @param $group
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function jsonResponse($data, $message, $code, $group)
    {
        $response = new JsonResponse();
        $response->headers->set('Content-Type', 'application/json');
        $responseData = $this->serialize([
            'message' => $message,
            'code' => $code,
            'data' => $data,
        ], 'json', $group);

        $response->setJson($responseData);
        return $response;
    }

    /**
     * Process the response code for the Exception.
     *
     * @param $exception
     * @param string $_format
     *
     * @return array
     */
    public function getExceptionOptions($exception, $_format = 'json')
    {

        $code = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : Response::HTTP_BAD_REQUEST;

        return array(
            'format' => $_format,
            'code' => $code,
            'message' => $exception->getMessage()
        );

    }

}