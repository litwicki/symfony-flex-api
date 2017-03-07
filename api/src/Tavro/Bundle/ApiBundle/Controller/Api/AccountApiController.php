<?php namespace Tavro\Bundle\ApiBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Tavro\Bundle\CoreBundle\Entity\Account;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiNotFoundException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiRequestLimitException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;

use Doctrine\Common\Inflector\Inflector;

use Litwicki\Common\Common;
use Tavro\Bundle\ApiBundle\Controller\DefaultController;

class AccountApiController extends DefaultController
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
    public function postAction(Request $request, Account $account, $entity, $_format)
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
    public function putAction(Request $request, Account $account, $entity, $id, $_format)
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
    public function typeaheadAction(Request $request, Account $account, $entity, $_format)
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
    public function getAllAction(Request $request, Account $account, $entity, $_format)
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
    public function getAction($entity, Account $account, $id, $_format)
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
    public function deleteAction(Request $request, Account $account, $entity, $id, $_format)
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