<?php

namespace Tavro\Bundle\CoreBundle\Controller\Api;

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

class DefaultController extends Controller
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

    public function toArray($object, $format = 'json', $group = 'api')
    {

        switch($format) {
            case 'xml':
                $string = $this->get('tavro_serializer')->serialize($object, $format, $group);
                return json_decode(json_encode((array) simplexml_load_string($string)), true);
                break;
            case 'json':
            default:
                return json_decode($this->get('tavro_serializer')->serialize($object, $format, $group), true);
                break;
        }

    }

    /**
     * Convert SimpleXMLObject to an Array.
     * @see: http://www.php.net/manual/en/ref.simplexml.php#111227
     *
     * @param $xmlObject
     * @param array $out
     *
     * @return array
     */
    public function xml2array($xmlObject, $out = array())
    {
        foreach ( (array) $xmlObject as $index => $node ) {
            $out[$index] = (is_object($node) || is_array($node)) ? $this->xml2array($node) : $node;
        }

        return $out;
    }

    /**
     * @param \SimpleXMLElement $object
     * @param array $data
     *
     * @return \SimpleXMLElement
     */
    public function toXml(\SimpleXMLElement $object, array $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $new_object = $object->addChild($key);
                $this->toXml($new_object, $value);
            }
            else {
                $object->addChild($key, $value);
            }
        }
        return $object;
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
     * @throws \Tavro\Bundle\CoreBundle\Exception\ApiException
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
     * @returns \Tavro\Bundle\CoreBundle\Handler\
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
    public function getAllAction(Request $request, $entity, $_format)
    {
        try {

            $params = $request->query->all();
            $handler = $this->getHandler($entity);
            $items = $handler->findAll($params);
            $data = $this->serialize($items, $_format);
            return $this->apiResponse($data, $_format);
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
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
     */
    public function getAction($entity, $id, $_format)
    {
        try {
            $handler = $this->getHandler($entity);
            $item = $handler->find($id);
            $data = $this->serialize($item, $_format);
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
     * @param $entity
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postAction(Request $request, $entity, $_format)
    {
        try {

            $data = json_decode($request->getContent(), true);

            $handler = $this->getHandler($entity);
            $newEntity = $handler->post($request, $data);

            $routeOptions = array(
                'entity'  => $entity,
                'id'      => $newEntity->getId(),
                'format'  => $_format,
            );

            return $this->forward('TavroCoreBundle:Default:get', $routeOptions);
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
    public function putAction(Request $request, $entity, $id, $_format)
    {
        try {

            $post = json_decode($request->getContent(), true);

            $handler = $this->getHandler($entity);

            if (!($item = $handler->find($id))) {
                $item = $handler->post($request, $item, $post);
            }
            else {
                $item = $handler->put($request, $item, $post);
            }

            $routeOptions = array(
                'entity'  => $entity,
                'id'      => $item->getId(),
                '_format'  => $_format,
            );

            return $this->forward('TavroCoreBundle:Default:get', $routeOptions);
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
     * @param $entity
     * @param $id
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function patchAction(Request $request, $entity, $id, $_format)
    {
        try {

            $patch = json_decode($request->getContent(), true);
            $handler = $this->getHandler($entity);
            $object = $handler->find($id);

            $handler->patch($request, $object, $patch);

            $mod = $handler->find($id);
            $data = $this->serialize($mod, 'json');
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