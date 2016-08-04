<?php

namespace Tavro\Bundle\ApiBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiNotFoundException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiRequestLimitException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Tavro\Bundle\CoreBundle\Entity\Expense;
use Tavro\Bundle\CoreBundle\Entity\ExpenseComment;
use Symfony\Component\HttpFoundation\Cookie;

use Litwicki\Common\Common;
use Tavro\Bundle\ApiBundle\Controller\DefaultController as ApiController;

class TagController extends ApiController
{

    /**
     * Post (create) a new Tag
     * But first... see if this Tag already exists, and if it does, return it for use.
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

            $handler = $this->getHandler($entity);
            $data = json_decode($request->getContent(), true);

            $tag = $handler->findByTag($data['tag']);

            $newEntity = $handler->post($data);

            $routeOptions = array(
                'entity'  => $entity,
                'id'      => $newEntity->getId(),
                'format'  => $_format,
            );

            return $this->forward('TavroCoreBundle:Default:get', $routeOptions);
        }
        catch (InvalidFormException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

}