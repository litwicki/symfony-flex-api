<?php

namespace Tavro\Bundle\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiNotFoundException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiRequestLimitException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;
use Symfony\Component\EventDispatcher\EventDispatcher;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Tavro\Bundle\CoreBundle\Entity\User;
use Symfony\Component\HttpFoundation\Cookie;

use Litwicki\Common\Common;
use Tavro\Bundle\ApiBundle\Controller\DefaultController as ApiController;

class UserController extends ApiController
{

    /**
     * Post (create) a new User
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
        $em = $this->getDoctrine()->getEntityManager();

        try {

            $em->getConnection()->beginTransaction();

            $data = json_decode($request->getContent(), true);
            $userHandler = $this->getHandler('users');
            $personHandler = $this->getHandler('people');

            $personData = [
                'first_name' => isset($data['first_name']) ? $data['first_name'] : null,
                'middle_name' => isset($data['middle_name']) ? $data['middle_name'] : null,
                'last_name' => isset($data['last_name']) ? $data['last_name'] : null,
                'email' => isset($data['email']) ? $data['email'] : null,
                'gender' => isset($data['gender']) ? $data['gender'] : null,
                'title' => isset($data['title']) ? $data['title'] : null,
                'suffix' => isset($data['suffix']) ? $data['suffix'] : null,
                'birthday' => isset($data['birthday']) ? $data['birthday'] : null,
                'address' => isset($data['address']) ? $data['address'] : null,
                'address2' => isset($data['address2']) ? $data['address2'] : null,
                'city' => isset($data['city']) ? $data['city'] : null,
                'state' => isset($data['state']) ? $data['state'] : null,
                'zip' => isset($data['zip']) ? $data['zip'] : null,
                'phone' => isset($data['phone']) ? $data['phone'] : null,
                'body' => isset($data['body']) ? $data['body'] : null,
                'status' => isset($data['status']) ? $data['status'] : null,
            ];

            /**
             * Separate the `Person` and `User` data to the separate requests.
             */
            $newPerson = $personHandler->post($request, $personData);

            $userData = [
                'person' => $newPerson->getId(),
                'username' => isset($data['username']) ? $data['username'] : null,
                'api_enabled' => isset($data['api_enabled']) ? $data['api_enabled'] : FALSE,
                'signature' => isset($data['signature']) ? $data['signature'] : null,
                'password' => isset($data['password']) ? $data['password'] : null,
                'status' => isset($data['status']) ? $data['status'] : null,
            ];

            $newUser = $userHandler->post($request, $userData);

            $routeOptions = array(
                'entity'  => $entity,
                'id'      => $newUser->getId(),
                'format'  => $_format,
            );

            $em->commit();

            /**
             * Finally, fire the UserRegisterEvent to handle post-signup logic
             */

            return $this->forward('TavroApiBundle:Default:get', $routeOptions);
        }
        catch(\Exception $e) {
            $em->getConnection()->rollBack();
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
        if(!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('You do not have permission to perform this action!');
        }

        try {

            $params = $request->query->all();
            $handler = $this->getHandler($entity);
            $items = $handler->findAll($params);
            $data = $this->serialize($items, $_format);
            return $this->apiResponse($data, $_format);
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

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
            $cookie = new Cookie('api_key', $user->getApiKey(), 0, '/', NULL, FALSE, FALSE);
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
            $cookie = new Cookie('api_password', $user->getApiPassword(), 0, '/', NULL, FALSE, FALSE);
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
        $entities = $this->getDoctrine()->getManager()->getRepository('TavroApiBundle:Organization')->findBy(array(
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