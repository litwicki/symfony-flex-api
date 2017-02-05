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

class ApiController extends Controller
{
    /**
     * @param $raw
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function apiResponse($raw, array $options = array())
    {
        try {

            $format = isset($options['format']) ? $options['format'] : 'json';
            $group = isset($options['group']) ? $options['group'] : 'api';
            $code = isset($options['code']) ? $options['code'] : 200;
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

        }
        catch(\Exception $e) {
            throw $e;
        }

        return $response;
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
            $items = $handler->findAll($params);
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
            $item = $handler->find($id);
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

            $routeOptions = array(
                'entity'  => $entity,
                'id'      => $newEntity->getId(),
                'format'  => $_format,
            );

            return $this->forward('TavroApiBundle:Default:get', $routeOptions);
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

            $post = json_decode($request->getContent(), TRUE);

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

            return $this->forward('TavroApiBundle:Default:get', $routeOptions);
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

            $patch = json_decode($request->getContent(), TRUE);
            $handler = $this->getHandler($entity);
            $object = $handler->find($id);

            $handler->patch($request, $object, $patch);

            $entity = $handler->find($id);
            return $this->apiResponse($entity, [
                'format' => $_format
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

            if ($data = $handler->find($id)) {
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