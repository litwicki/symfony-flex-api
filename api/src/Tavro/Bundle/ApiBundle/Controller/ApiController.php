<?php

namespace Tavro\Bundle\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Tavro\Bundle\ApiBundle\Exception\ApiException;
use Tavro\Bundle\ApiBundle\Exception\ApiNotFoundException;
use Tavro\Bundle\ApiBundle\Exception\ApiRequestLimitException;
use Tavro\Bundle\ApiBundle\Exception\ApiAccessDeniedException;

use Doctrine\Common\Inflector\Inflector;

use Litwicki\Common\Common;

class ApiController extends Controller
{
    /**
     * @param $data
     * @param string $format
     * @param int $code
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function apiResponse($data, $format = 'json', $code = 200)
    {
        try {

            $response = new Response($data);

            if($format == 'json') {
                $response->headers->set('Content-Type', 'application/json');
            }
            else {
                $response->headers->set('Content-Type', 'application/xml');
            }

            $response->setStatusCode($code);

        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }

        return $response;
    }

    /**
     * @param $entity
     * @param $id
     *
     * @return mixed
     */
    protected function findOr404($entity, $id)
    {
        if (!($entity = $this->container->get('tavro.handler.' . $entity)->get($id))) {
            throw new ApiNotFoundException(sprintf('The resource \'%s\' was not found.', $id));
        }

        return $entity;
    }

    /**
     * Serialize an Entity or array of Entities.
     *
     * @param $data
     * @param string $format
     * @param $string $group
     *
     * @throws \Tavro\Bundle\ApiBundle\Exception\ApiException
     */
    protected function serialize($data, $format = 'json', $group = 'api')
    {
        try {
            $serializer = $this->container->get('tavro_serializer');
            return $serializer->serialize($data, $format, $group);
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * Fetch the proper handler for an entity by its route name.
     *
     * @param $entityName
     *
     * @returns \Tavro\Bundle\ApiBundle\Handler\
     */
    public function getHandler($entityName)
    {
        try {
            $service = sprintf('tavro.handler.%s', $entityName);
            $handler = $this->container->get($service);
            return $handler;
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $entity
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws
     */
    public function getUserEntitiesAction(Request $request, $entity, $_format)
    {
        try {
            $page = $request->query->get('page');
            $size = $request->query->get('size');
            $handler = $this->getHandler($entity);
            $items = $handler->findByUser($this->getUser(), true, $size, $page);
            $data = $this->serialize($items, $_format);
            return $this->apiResponse($data, $_format);
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
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
     */
    public function typeaheadAction(Request $request, $entity, $_format)
    {
        try {
            $params = $request->query->all();
            $handler = $this->getHandler($entity);
            $items = $handler->typeahead($params);
            $data = $this->serialize($items, $_format, 'typeahead');
            return $this->apiResponse($data, $_format);
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
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
    public function getAllAction(Request $request, $group, $entity, $_format)
    {
        try {

            $params = $request->query->all();
            $handler = $this->getHandler($entity);
            $items = $handler->findAll($params);
            $data = $this->serialize($items, $_format, $group);
            return $this->apiResponse($data, $_format);
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * Get (find) an entity by Id.
     *
     * @param $group
     * @param $entity
     * @param $id
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction($group, $entity, $id, $_format)
    {
        try {
            $handler = $this->getHandler($entity);
            $item = $handler->find($id);
            $data = $this->serialize($item, $_format, $group);
            return $this->apiResponse($data, $_format);
        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * Post (create) a new Entity
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $group
     * @param $entity
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postAction(Request $request, $group, $entity, $_format)
    {
        try {

            $data = json_decode($request->getContent(), true);

            $handler = $this->getHandler($entity);
            $newEntity = $handler->post($data);

            $routeOptions = array(
                'group'   => $group,
                'entity'  => $entity,
                'id'      => $newEntity->getId(),
                'format'  => $_format,
            );

            return $this->forward('TavroApiBundle:Default:get', $routeOptions);
        }
        catch (InvalidFormException $e) {
            throw $e;
//            $form = $e->getForm();
//            $error = (string) $form->getErrors(true, false);
//            throw new InvalidFormException($error);
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
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
    public function putAction(Request $request, $group, $entity, $id, $_format)
    {
        try {

            $post = json_decode($request->getContent(), true);

            $handler = $this->getHandler($entity);

            if (!($item = $handler->find($id))) {
                $item = $handler->post($item, $post);
            }
            else {
                $item = $handler->put($item, $post);
            }

            $routeOptions = array(
                'group' => $group,
                'entity'  => $entity,
                'id'      => $item->getId(),
                '_format'  => $_format,
            );

            return $this->forward('TavroApiBundle:Default:get', $routeOptions);
        }
        catch (InvalidFormException $e) {
            throw $e;
        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            //throw new ApiException($e->getMessage());
            throw $e;
        }
    }

    /**
     * Patch an Entity.
     * Fundamentally different from Update specifically by
     * the permission sets used to apply the PATCH.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $group
     * @param $entity
     * @param $id
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function patchAction(Request $request, $group, $entity, $id, $_format)
    {
        try {

            $patch = json_decode($request->getContent(), true);
            $handler = $this->getHandler($entity);
            $object = $handler->find($id);

            $handler->patch($object, $patch);

            $mod = $handler->find($id);
            $data = $this->serialize($mod, 'json', $group);
            return $this->apiResponse($data);

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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $entity
     * @param $id
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request, $entity, $id, $_format)
    {
        try {

            $handler = $this->getHandler($entity);

            $class = Inflector::singularize($entity);
            $class = Inflector::classify($class);

            if ($data = $handler->find($id)) {
                $handler->delete($data);
                $code = 200;
                $message = sprintf('%s %s deleted.', $class, $id);
            }
            else {
                $code = 400;
                $message = sprintf('%s object not found.', $class);
            }

            $data = array(
                'code' => $code,
                'message' => $message
            );

            return $this->apiResponse($_format == 'json' ? json_encode($data) : $data, $_format, $code);

        }
        catch (InvalidFormException $e) {
            throw $e;
        }
        catch (\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

}