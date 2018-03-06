<?php namespace App\Controller\Entity;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use App\Entity\User;
use App\Controller\Api\EntityApiController;
use App\Exception\UsernameNotUniqueException;

class UserController extends EntityApiController
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

        $data = null;

        try {

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

            $data = $newUser;

            $options = [
                'format' => $_format,
                'group' => 'simple',
                'code' => Response::HTTP_CREATED,
                'message' => sprintf('User `%s` with email %s signup complete!', $newUser->__toString(), $person->getEmail())
            ];

        }
        catch(\Exception $e) {
            $options = $this->getExceptionOptions($e, $_format);
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
    public function changePasswordAction(Request $request, $entity, $_format)
    {
        $data = null;

        try {

            $data = $this->getPayload($request);
            $handler = $this->getHandler($entity);
            $user = $handler->changePassword($request, $data);

            $data = [
                'token' => $this->get('lexik_jwt_authentication.encoder')->encode(['username' => $user->getUsername()])
            ];

            $options = [
                'message' => sprintf('Password changed for User %s', $user->__toString()),
                'code' => Response::HTTP_OK,
                'format' => $_format
            ];

        }
        catch(\Exception $e) {
            $options = $this->getExceptionOptions($e, $_format);
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
        if(!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('You do not have permission to perform this action!');
        }

        $data = null;

        try {

            $params = $request->query->all();
            $handler = $this->getHandler($entity);
            $data = $handler->getAll($params);

            $options = [
                'format' => $_format,
                'group' => 'simple'
            ];
        }
        catch(\Exception $e) {
            $options = $this->getExceptionOptions($e, $_format);
        }

        return $this->apiResponse($data, $options);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\User $user
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function accountsAction(Request $request, User $user, $_format)
    {

        $data = null;

        try {

            $accounts = $user->getAccountUsers();

            $data = array();

            foreach($accounts as $entity) {
                $account = $entity->getAccount();
                $data[$account->getId()] = $account;
            }

            /**
             * Cross Reference every Organization this User owns but may not be
             * a "User" of..
             */
            $entities = $this->getDoctrine()->getManager()->getRepository('TavroApiBundle:Account')->findBy(array(
                'user' => $user
            ));

            foreach($entities as $entity) {
                $data[$entity->getId()] = $entity;
            }

            $options = [
                'code' => Response::HTTP_OK,
                'format' => $_format
            ];

        }
        catch(\Exception $e) {
            $options = $this->getExceptionOptions($e, $_format);
        }

        return $this->apiResponse($data, $options);

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
        $data = null;

        try {
            $data = $this->getUser();

            $options = [
                'format' => $_format,
                'group' => 'simple'
            ];
        }
        catch(\Exception $e) {
            $options = $this->getExceptionOptions($e, $_format);
        }

        return $this->apiResponse($data, $options);
    }

}