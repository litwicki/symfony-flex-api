<?php namespace Tavro\Bundle\ApiBundle\Controller\Api;

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
use Tavro\Bundle\ApiBundle\Controller\DefaultController;

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
    public function postAction(Request $request, $entity, $_format)
    {
        try {

            $data = json_decode($request->getContent(), TRUE);
            $handler = $this->getHandler($entity);
            $newEntity = $handler->post($request, $data);

            return $this->apiResponse($newEntity, [
                'code' => Response::HTTP_CREATED,
            ]);

        }
        catch (InvalidFormException $e) {
            throw $e;
        }
        catch (ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
        }
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
    public function putAction(Request $request, $entity, $id, $_format)
    {
        try {

            $data = json_decode($request->getContent(), TRUE);

            $handler = $this->getHandler($entity);

            if (!($item = $handler->get($id))) {
                $item = $handler->post($request, $item, $data);
                $responseCode = Response::HTTP_CREATED;
            }
            else {
                $item = $handler->put($request, $item, $data);
                $responseCode = Response::HTTP_OK;
            }

            return $this->apiResponse($item, [
                'code' => $responseCode,
            ]);

        }
        catch (InvalidFormException $e) {
            throw $e;
        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
        }
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
    public function typeaheadAction(Request $request, $entity, $_format)
    {
        try {
            $params = $request->query->all();
            $handler = $this->getHandler($entity);
            $items = $handler->typeahead($params);
            return $this->apiResponse($items, [
                'format' => $_format,
                'group' => 'typeahead'
            ]);
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $entity
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function getAllAction(Request $request, $entity, $_format)
    {
        try {

            $params = $request->query->all();
            $handler = $this->getHandler($entity);
            $items = $handler->getAll($params);
            return $this->apiResponse($items, [
                'format' => $_format
            ]);
        }
        catch(\Exception $e) {
            throw $e;
        }
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
    public function getAction($entity, $id, $_format)
    {
        try {
            $handler = $this->getHandler($entity);
            $item = $handler->get($id);
            return $this->apiResponse($item, [
                'format' => $_format
            ]);
        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
        }
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
    public function deleteAction(Request $request, $entity, $id, $_format)
    {
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

            return $this->apiResponse($data, [
                'format' => $_format,
                'code' => $code,
                'message' => $message
            ]);

        }
        catch (InvalidFormException $e) {
            throw $e;
        }
        catch (\Exception $e) {
            throw $e;
        }
    }

}