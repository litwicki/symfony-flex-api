<?php

namespace Tavro\Bundle\CoreBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiNotFoundException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiRequestLimitException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Tavro\Bundle\CoreBundle\Entity\User;
use Symfony\Component\HttpFoundation\Cookie;

use Litwicki\Common\Common;
use Tavro\Bundle\CoreBundle\Controller\Api\DefaultController as ApiController;

class UserController extends ApiController
{
    /**
     * @param Request $request
     * @param User $user
     * @param $_format
     * @return Response
     */
    public function resetApiKeyAction(Request $request, User $user, $_format)
    {
        try {
            $handler = $this->container->get('tavro.handler.users');
            $handler->resetApiKey($user);
            $cookie = new Cookie('api_key', $user->getApiKey(), 0, '/', NULL, false, false);
            $data = $this->serialize($user, $_format);
            $response = $this->apiResponse($data, $_format);
            $response->headers->setCookie($cookie);
            $handler->reauthenticate($user);
            return $response;
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param User $user
     * @param $_format
     * @return Response
     */
    public function resetApiPasswordAction(Request $request, User $user, $_format)
    {
        try {
            $handler = $this->container->get('tavro.handler.users');
            $handler->resetApiPassword($user);
            $cookie = new Cookie('api_password', $user->getApiPassword(), 0, '/', NULL, false, false);
            $data = $this->serialize($user, $_format);
            $response = $this->apiResponse($data, $_format);
            $response->headers->setCookie($cookie);
            $handler->reauthenticate($user);
            return $response;
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function organizationsAction(Request $request, User $user, $_format)
    {

        $uos = $user->getUserOrganizations();

        $items = array();

        foreach($uos as $uo) {
            $org = $uo->getOrganization();
            $items[$org->getId()] = $org;
        }

        /**
         * Cross Reference every Organization this User owns but may not be
         * a "User" of..
         */
        $entities = $this->getDoctrine()->getManager()->getRepository('TavroCoreBundle:Organization')->findBy(array(
            'user' => $user
        ));

        foreach($entities as $entity) {
            $items[$entity->getId()] = $entity;
        }

        $data = $this->serialize($items, $_format);
        $response = $this->apiResponse($data, $_format);
        return $response;

    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function nodesAction(Request $request, User $user, $_format)
    {
        $nodes = $user->getNodes();
        $data = $this->serialize($nodes, $_format);
        $response = $this->apiResponse($data, $_format);
        return $response;
    }

}