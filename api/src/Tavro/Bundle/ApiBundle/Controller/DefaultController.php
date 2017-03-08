<?php

namespace Tavro\Bundle\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
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

        $response = new Response();

        if($format == 'json') {
            $response->headers->set('Content-Type', 'application/json');
            $responseData = $this->serialize([
                'message' => $message,
                'data' => $raw,
            ], $format, $group);
        }
        else {
            $response->headers->set('Content-Type', 'application/xml');
            $responseData = [];
        }

        $response->setContent($responseData);
        $response->setStatusCode($code);

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

    /**
     * Post (create) a new Entity
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $entity
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function post(Request $request, $entity, $_format)
    {

        $data = null;

        try {

            $data = json_decode($request->getContent(), TRUE);
            $handler = $this->getHandler($entity);
            $newEntity = $handler->post($request, $data);

            $data = $newEntity;
            $options = [
                'format' => $_format,
                'message' => sprintf('New %s created successfully.', $entity),
                'code' => Response::HTTP_CREATED
            ];

        }
        catch(\Exception $e) {
            $options = $this->getExceptionOptions($e, $_format);
        }

        return $this->apiResponse($data, $options);

    }

    /**
     * CREATE a new Entity if $id does not exist, otherwise PUT (update) existing Entity.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $entity
     * @param $id
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function put(Request $request, $entity, $id, $_format)
    {
        $data = null;

        try {

            $data = json_decode($request->getContent(), TRUE);

            $handler = $this->getHandler($entity);

            if (!($item = $handler->get($id))) {
                $data = $handler->post($request, $item, $data);
                $responseCode = Response::HTTP_CREATED;
            }
            else {
                $data = $handler->put($request, $item, $data);
                $responseCode = Response::HTTP_OK;
            }

            $options = [
                'format' => $_format,
                'code' => $responseCode
            ];

        }
        catch(\Exception $e) {
            $options = $this->getExceptionOptions($e, $_format);
        }

        return $this->apiResponse($data, $options);

    }

    /**
     * Fetch a list of entities based on passed parameters that can be displayed
     * in a typeahead autocomplete widget.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $entity
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function typeahead(Request $request, $entity, $_format)
    {

        $data = null;

        try {

            $params = $request->query->all();
            $handler = $this->getHandler($entity);
            $data = $handler->typeahead($params);

            $options = [
                'format' => $_format,
                'group' => 'typeahead'
            ];

        }
        catch(\Exception $e) {
            $options = $this->getExceptionOptions($e, $_format);
        }

        return $this->apiResponse($data, $options);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $entity
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function getAll(Request $request, $entity, $_format)
    {

        $data = null;

        try {

            $params = $request->query->all();
            $handler = $this->getHandler($entity);
            $response = $handler->getAll($params);

            $data = $response['data'];
            $message = $response['message'];

            $options = [
                'format' => $_format,
                'message' => $message,
            ];

        }
        catch(\Exception $e) {
            $options = $this->getExceptionOptions($e, $_format);
        }

        return $this->apiResponse($data, $options);

    }

    /**
     * Get (find) an entity by Id.
     *
     * @param $entity
     * @param $id
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function get($entity, $id, $_format)
    {

        $data = null;

        try {
            $handler = $this->getHandler($entity);
            $data = $handler->get($id);
            $options = [
                'format' => $_format
            ];
        }
        catch(\Exception $e) {
            $options = $this->getExceptionOptions($e, $_format);
        }

        return $this->apiResponse($data, $options);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $entity
     * @param $id
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function delete(Request $request, $entity, $id, $_format)
    {

        $data = null;

        try {

            $handler = $this->getHandler($entity);

            $class = Inflector::singularize($entity);
            $class = Inflector::classify($class);

            if ($data = $handler->get($id)) {
                $handler->delete($request, $data);
                $code = 200;
                $message = sprintf('%s %s deleted.', $class, $id);
            }
            else {
                $code = 404;
                $message = sprintf('%s object not found.', $class);
            }

            $data = [
                'message' => $message
            ];

            $options = [
                'format' => $_format,
                'code' => $code,
                'message' => $message
            ];

        }
        catch(\Exception $e) {
            $options = $this->getExceptionOptions($e, $_format);
        }

        return $this->apiResponse($data, $options);
    }

}