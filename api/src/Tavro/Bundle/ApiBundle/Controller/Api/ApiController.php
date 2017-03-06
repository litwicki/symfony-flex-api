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
        catch (InvalidFormException $e) {
            $options = [
                'format' => $_format,
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage()
            ];
        }
        catch (ApiAccessDeniedException $e) {
            $options = [
                'format' => $_format,
                'code' => Response::HTTP_FORBIDDEN,
                'message' => $e->getMessage()
            ];
        }
        catch(\Exception $e) {
            $options = [
                'format' => $_format,
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ];
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
    public function putAction(Request $request, $entity, $id, $_format)
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
        catch (InvalidFormException $e) {
            $options = [
                'format' => $_format,
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage()
            ];
        }
        catch (ApiAccessDeniedException $e) {
            $options = [
                'format' => $_format,
                'code' => Response::HTTP_FORBIDDEN,
                'message' => $e->getMessage()
            ];
        }
        catch(\Exception $e) {
            $options = [
                'format' => $_format,
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ];
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
    public function typeaheadAction(Request $request, $entity, $_format)
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
        catch (InvalidFormException $e) {
            $options = [
                'format' => $_format,
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage()
            ];
        }
        catch (ApiAccessDeniedException $e) {
            $options = [
                'format' => $_format,
                'code' => Response::HTTP_FORBIDDEN,
                'message' => $e->getMessage()
            ];
        }
        catch(\Exception $e) {
            $options = [
                'format' => $_format,
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ];
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
    public function getAllAction(Request $request, $entity, $_format)
    {
        try {

            $params = $request->query->all();
            $handler = $this->getHandler($entity);
            $response = $handler->getAll($params);

            $items = $response['data'];
            $message = $response['message'];

            return $this->apiResponse($items, [
                'format' => $_format,
                'message' => $message,
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