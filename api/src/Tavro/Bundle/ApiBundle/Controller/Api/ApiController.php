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
     * Get an Entity - forward to the appropriate controller to handle.
     *
     * @param $entity
     * @param $id
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction($entity, $id, $_format)
    {
        return $this->forward('TavroApiBundle:Api\Get:get', [
            'entity'  => $entity,
            'id' => $id,
            '_format' => $_format
        ]);
    }

    /**
     * Get All Entities - forward to the appropriate handler.
     *
     * @param $entity
     * @param $id
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAllAction($entity, $id, $_format)
    {
        return $this->forward('TavroApiBundle:Api\Get:getAll', [
            'entity'  => $entity,
            'id' => $id,
            '_format' => $_format
        ]);
    }

    /**
     * PUT an Entity by redirecting to the PUT Controller.
     *
     * @param $entity
     * @param $id
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putAction($entity, $id, $_format)
    {
        return $this->forward('TavroApiBundle:Api\Put:put', [
            'entity'  => $entity,
            'id' => $id,
            '_format' => $_format
        ]);
    }

    public function postAction($entity, $id, $_format)
    {
        return $this->forward('TavroApiBundle:Api\Post:post', [
            'entity'  => $entity,
            'id' => $id,
            '_format' => $_format
        ]);
    }

    /**
     * PATCH an Entity by forwarding to the Patch Controller.
     *
     * @param $entity
     * @param $id
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function patchAction($entity, $id, $_format)
    {
        return $this->forward('TavroApiBundle:Api\Patch:patch', [
            'entity'  => $entity,
            'id' => $id,
            '_format' => $_format
        ]);
    }

}