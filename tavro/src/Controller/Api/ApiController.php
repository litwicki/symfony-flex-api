<?php namespace Tavro\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Tavro\Entity\Account;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Tavro\Exception\ApiNotFoundException;
use Tavro\Model\EntityInterface\EntityInterface;
use Tavro\Model\HandlerInterface\AccountEntityHandlerInterface;

use Doctrine\Common\Inflector\Inflector;

use Litwicki\Common\Common;
use Tavro\Controller\DefaultController;

class ApiController extends DefaultController
{
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
    public function _post(Request $request, $entity, $_format)
    {

        $data = null;

        try {

            $data = $this->getPayload($request);
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
    public function _put(Request $request, $entity, $id, $_format)
    {
        $data = null;

        try {

            $data = $this->getPayload($request);

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
     * PATCH: an entity.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $entity
     * @param $id
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function _patch(Request $request, $entity, $id, $_format)
    {
        $data = null;

        try {

            $data = $this->getPayload($request);

            $handler = $this->getHandler($entity);

            if (!($item = $handler->get($id))) {
                throw new ApiNotFoundException(sprintf('No % found with Id %s', $entity, $id));
            }

            $data = $handler->patch($request, $item, $data);
            $responseCode = Response::HTTP_OK;

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
    public function _typeahead(Request $request, $entity, $_format)
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
    public function _getAll(Request $request, $entity, $_format = 'json')
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Entity\Account $account
     * @param $entity
     * @param string $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function _getAllByAccount(Request $request, Account $account, $entity, $_format = 'json')
    {
        $data = null;

        try {

            $params = $request->query->all();
            $handler = $this->getHandler($entity);

            if(false === ($handler instanceof AccountEntityHandlerInterface)) {

            }

            $response = $handler->getAllByAccount($account, $params);

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
    public function _get(Request $request, $entity, $id, $_format)
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
    public function _delete(Request $request, $entity, $id, $_format)
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