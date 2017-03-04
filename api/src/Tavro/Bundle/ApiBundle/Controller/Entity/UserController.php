<?php

namespace Tavro\Bundle\ApiBundle\Controller\Entity;

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
use Symfony\Component\HttpFoundation\JsonResponse;

use Litwicki\Common\Common;
use Tavro\Bundle\ApiBundle\Controller\Api\ApiController as ApiController;

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
        $em = $this->getDoctrine()->getManager();

        try {

            $em->getConnection()->beginTransaction();

            $data = json_decode($request->getContent(), true);
            $userHandler = $this->getHandler('users');
            $personHandler = $this->getHandler('people');

            $personData = $data['person'];
            unset($data['person']);

            $userData = $data;

            /**
             * Separate the `Person` and `User` data to the separate requests.
             */
            $person = $personHandler->post($request, $personData);

            $userData['person'] = $person->getId();

            $newUser = $userHandler->post($request, $userData);

            $em->commit();

            return $this->apiResponse($newUser, [
                'format' => $_format,
                'group' => 'simple',
                'code' => Response::HTTP_CREATED,
                'message' => sprintf('User `%s` with email %s signup complete!', $newUser->__toString(), $person->getEmail())
            ]);

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
            $items = $handler->getAll($params);

            return $this->apiResponse($items, [
                'format' => $_format,
                'group' => 'simple'
            ]);
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function accountsAction(Request $request, User $user, $_format)
    {

        $accounts = $user->getAccountUsers();

        $items = array();

        foreach($accounts as $entity) {
            $account = $entity->getAccount();
            $items[$account->getId()] = $account;
        }

        /**
         * Cross Reference every Organization this User owns but may not be
         * a "User" of..
         */
        $entities = $this->getDoctrine()->getManager()->getRepository('TavroApiBundle:Account')->findBy(array(
            'user' => $user
        ));

        foreach($entities as $entity) {
            $items[$entity->getId()] = $entity;
        }

        return $this->apiResponse($items, [
            'format' => $_format,
            'group' => 'simple'
        ]);

    }

    /**
     * GET the current user.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function getCurrentAction(Request $request, $_format)
    {
        try {
            $user = $this->getUser();

            return $this->apiResponse($user, [
                'format' => $_format,
                'group' => 'simple'
            ]);
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

}