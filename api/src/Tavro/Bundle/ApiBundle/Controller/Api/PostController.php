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

class PostController extends ApiController
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

}