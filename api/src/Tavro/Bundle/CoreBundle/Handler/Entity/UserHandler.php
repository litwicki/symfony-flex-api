<?php

namespace Tavro\Bundle\CoreBundle\Handler\Entity;

use Rhumsaa\Uuid\Uuid;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Tavro\Bundle\CoreBundle\Event\UserRegisterEvent;
use Tavro\Bundle\CoreBundle\Exception\Password\PasswordLengthException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\HttpFoundation\Request;

use Tavro\Bundle\CoreBundle\Exception\InvalidUsernameException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Model\EntityHandler;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\UsernameNotUniqueException;
use Tavro\Bundle\CoreBundle\Exception\EmailNotUniqueException;
use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Entity\Role;
use Tavro\Bundle\CoreBundle\Component\Form\FormErrors;

/**
 * Class UserHandler
 *
 * @package Tavro\Bundle\CoreBundle\Handler
 */
class UserHandler extends EntityHandler
{

    /**
     * Reauthenticate the User with a fresh Token.
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     * @param string $firewall
     *
     * @throws \Exception
     */
    public function reauthenticate(User $user, $firewall = 'main')
    {
        try {
            $token = new UsernamePasswordToken($user, $user->getPassword(), $firewall, $user->getRoles());
            $this->tokenStorage->setToken($token);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $parameters
     *
     * @return object|void
     * @throws \Exception
     */
    public function create(Request $request, array $parameters)
    {
        try {

            if(!isset($parameters['status'])) {
                $parameters['status'] = self::STATUS_PENDING;
            }

            /**
             * If there are no roles defined, default to ROLE_USER
             */
            if (empty($parameters['roles'])) {
                $roles = ['ROLE_USER'];
            }
            else {
                $roles = $parameters['roles'];
                unset($parameters['roles']);
            }

            $entity = $this->processForm($request, $this->createEntity(), $parameters, self::HTTP_METHOD_POST);

            $this->setUserRoles($entity, $roles);

            return $entity;

        }
        catch (InvalidFormException $e) {
            throw $e;
        }
        catch (ApiAccessDeniedException $e) {
            throw $e;
        }
        catch (TransformationFailedException $e) {
            throw $e;
        }
        catch (UnexpectedTypeException $e) {
            throw $e;
        }
        catch (InvalidPropertyPathException $e) {
            throw $e;
        }
        catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $entity
     * @param array $parameters
     * @param string $method
     *
     * @throws \Exception
     * @throws \Symfony\Component\Debug\Exception\ContextErrorException
     */
    public function processForm(Request $request, EntityInterface $entity, array $parameters, $method = self::HTTP_METHOD_POST)
    {
        try {

            $this->validate($entity, $parameters, $method);

            $formType = $this->mapEntityToForm($this->entityClass);

            $form = $this->formFactory->create($formType, $entity, ['method' => $method]);

            /**
             * @reference: http://symfony.com/doc/current/form/direct_submit.html
             *           docs say this is required, but wtf?
             *
             *           $form->handleRequest($request);
             *
             */

            $form->submit($parameters, ($method == 'PATCH' ? false : true));

            if ($form->isValid()) {

                $entity = $form->getData();

                if (is_object($this->tokenStorage->getToken())) {
                    switch ($method) {

                        case 'POST':
                            if (!($this->auth->isGranted('create', $entity))) {
                                throw new ApiAccessDeniedException('You are not authorized to create a new User!');
                            }
                            break;

                        case 'PUT':
                            if (!($this->auth->isGranted('edit', $entity))) {
                                throw new ApiAccessDeniedException('You are not authorized to edit this User.');
                            }
                            break;

                        case 'DELETE':
                            if (!($this->auth->isGranted('delete', $entity))) {
                                throw new ApiAccessDeniedException('You are not authorized to remove this User!');
                            }
                            break;

                    }
                }

                if (isset($parameters['password'])) {
                    $password = $this->encoderFactory->getEncoder($entity)->encodePassword($parameters['password'], $entity->getSalt());
                    $entity->setPassword($password);
                }

                $this->om->persist($entity);
                $this->om->flush();

                return $entity;

            }
            else {
                $formErrors = new FormErrors();
                $errors = $formErrors->getArray($form);
                $exception = $formErrors->getErrorsAsString($errors);
                throw new InvalidFormException($exception);
            }

        }
        catch (TransformationFailedException $e) {
            throw $e;
        }
        catch (ContextErrorException $e) {
            throw $e;
        }
        catch (UnexpectedTypeException $e) {
            throw $e;
        }
        catch (InvalidPropertyPathException $e) {
            throw $e;
        }
        catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $roleName
     *
     * @return \Doctrine\Common\Collections\Collection
     * @throws \Exception
     */
    public function findByRole($roleName)
    {
        try {

            $role = $this->om->getRepository('TavroCoreBundle:Role')->findOneBy([
                'role' => $roleName,
            ]);

            return $role->getUsers();

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get a list of *all* Roles with a flag for ones the current User has actively.
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     *
     * @return array $items
     * @throws \Exception
     */
    public function getUserRoles(User $user)
    {
        try {

            $roles = $this->om->getRepository('TavroCoreBundle:Role')->findAll();
            $items = [];

            foreach ($user->getRoles() as $role) {
                $ids[] = $role->getId();
            }

            foreach ($roles as $role) {

                $items[] = [
                    'id'       => $role->getId(),
                    'role'     => $role->getRole(),
                    'name'     => $role->getName(),
                    'user_has' => in_array($role->getId(), $ids) ? true : false,
                ];

            }

            return $items;

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Find all Entities (limit the response size)
     * Optionally page the result set by LIMIT and OFFSET.
     *
     * @param array $params
     *
     * @return array|void
     */
    public function findAll(array $params = null)
    {
        try {

            $page = isset($params['page']) ? $params['page'] : 1;
            $size = isset($params['size']) ? $params['size'] : self::PAGE_SIZE;

            $sort = (isset($params['sort'])) ? $params['sort'] : 'desc';
            $orderBy = (isset($params['orderBy'])) ? $params['orderBy'] : 'id';

            $sortOrder = [$orderBy => $sort];

            if (!isset($params['status'])) {
                $params['status'] = self::STATUS_ACTIVE; //@TODO: Make this a constant fetched from Model\Entity.php
            }

            $offset = ($page - 1) * $size;

            $params = $this->filterParams($params);

            $entities = $this->repository->findBy(
                $params,
                $sortOrder,
                $size,
                $offset
            );

            $items = [];

            foreach ($entities as $entity) {
                if ($this->auth->isGranted('view', $entity)) {
                    $items[] = $entity;
                }
            }

            /**
             * Filter out Users that are not
             */

            return $items;

        } catch (ApiAccessDeniedException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     * @param array $roles
     *
     * @throws \Exception
     */
    public function setUserRolesById(User $user, array $roles)
    {
        try {

            $repository = $this->om->getRepository('TavroCoreBundle:Role');

            /**
             * Remove all Roles from the User.
             */
            foreach ($user->getRoles() as $role) {
                $user->removeRole($role);
                $this->om->persist($user);
            }

            /**
             * Add every role in the assigned array to this User.
             */
            foreach ($roles as $rid) {
                $role = $repository->find($rid);
                $user->addRole($role);
                $this->om->persist($user);
            }

            $this->om->flush();

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Filter out parameters we don't want to give access to.
     *
     * @param array $params
     *
     * @return array
     * @throws \Exception
     */
    public function filterParams(array $params)
    {
        try {

            $parameters = [];
            $options = ['status', 'username', 'email'];

            foreach ($params as $name => $value) {
                if (in_array($name, $options)) {
                    $parameters[$name] = $value;
                }
            }

            return $parameters;

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     * @param array $roles
     *
     * @throws \Exception
     */
    public function setUserRolesByName(User $user, array $roles)
    {
        try {

            /**
             * Remove all Roles from the User.
             */
            foreach ($user->getRoles() as $role) {
                $user->removeRole($role);
                $this->om->persist($user);
            }

            /**
             * Add every role in the assigned array to this User.
             */
            foreach ($roles as $roleName) {
                $role = $this->findRoleByName($roleName);
                $user->addRole($role);
                $this->om->persist($user);
            }

            $this->om->flush();

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     * @param array $roles
     */
    public function setUserRoles(User $user, array $roles)
    {
        foreach ($roles as $role) {
            if (is_numeric($role)) {
                $this->setUserRolesById($user, $roles);

                return;
            } else {
                $this->setUserRolesByName($user, $roles);

                return;
            }
        }
    }

    /**
     * @param $roleName
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Role
     * @throws \Exception
     */
    public function findRoleByName($roleName)
    {
        try {

            $role = $this->om->getRepository('TavroCoreBundle:Role')->findOneBy([
                'role' => $roleName,
            ]);

            if ($role instanceof Role) {
                return $role;
            } else {
                throw new \Exception(sprintf('No role found with name %s', $roleName));
            }

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $user
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface
     * @throws \Exception
     */
    public function resetApiKey(EntityInterface $user)
    {
        try {

            $user->resetApiKey();
            $this->om->persist($user);
            $this->om->flush();

            $this->reauthenticate($user);

            return $user;

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $user
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface
     * @throws \Exception
     */
    public function resetApiPassword(EntityInterface $user)
    {
        try {

            $user->resetApiPassword();
            $this->om->persist($user);
            $this->om->flush();

            $this->reauthenticate($user);

            return $user;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param array $parameters
     *
     * @return array
     * @throws \Exception
     */
    public function typeahead(array $parameters)
    {
        try {

            if (isset($parameters['username'])) {

                $query = $this->om->createQuery(
                    'SELECT u FROM TavroCoreBundle:User u WHERE u.username LIKE :username'
                );

                $query->setParameter('username', '%' . $parameters['username'] . '%');

                $entities = $query->getResult();

            } else {
                $entities = $this->repository->findAll();
            }

            $items = [];

            foreach ($entities as $entity) {
                if ($this->auth->isGranted('view', $entity)) {
                    $items[] = $entity;
                }
            }

            return $items;

        } catch (ApiAccessDeniedException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     *
     * @throws \Exception
     */
    public function setPasswordToken(User $user)
    {
        try {

            $expiration = new \DateTime();
            $interval = new \DateInterval('PT60M');
            $expiration = $expiration->add($interval);

            $user->setPasswordToken(Uuid::uuid1()->toString());
            $user->setPasswordTokenExpire($expiration);
            $this->om->persist($user);
            $this->om->flush();

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     * @param $parameters
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface
     * @throws \Exception
     */
    public function resetPassword(Request $request, User $user, $parameters)
    {
        try {

            if(!isset($parameters['password'])) {
                throw new PasswordLengthException('Password not found');
            }

            if(!isset($parameters['password_confirm']) || !isset($parameters['new_password'])) {
                throw new \Exception('New password not defined and/or matched!');
            }

            $parameters = [
                'password_token'        => null,
                'password_token_expire' => null,
                'password'              => $parameters['password'],
            ];

            return $this->patch($request, $user, $parameters);

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $entity
     * @param array $parameters
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface|void
     * @throws \Exception
     */
    public function patch(Request $request, EntityInterface $entity, array $parameters)
    {
        try {

            if (!$this->auth->isGranted('patch', $entity)) {
                $message = sprintf('Unable to properly "patch" %s: %s', get_class($entity), $entity->__toString());
                throw new ApiAccessDeniedException($message);
            }

            $user = $this->applyPatch($request, $entity, $parameters);

            $this->reauthenticate($user);

            return $user;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Validate the uniqueness of an Email address.
     *
     * @param $email
     */
    public function validateUniqueEmail($email)
    {
        if ($this->repository->findOneBy(['email' => $email])) {
            throw new EmailNotUniqueException(
                sprintf(
                    'User with email (%s) already exists!',
                    $email
                )
            );
        }
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $user
     * @param array $parameters
     * @param string $method
     *
     * @throws \Exception
     */
    public function validate(EntityInterface $user, array $parameters = [], $method = 'POST')
    {

        try {

            foreach ($parameters as $key => $value) {

                switch ($key) {

                    case 'email':
                        $this->validateEmail($user, $email = $value, $this->repository);
                        break;

                    case 'username':

                        //validate this username isn't in use by another user
                        $this->validateUsernameUnique($user, $username = $value, $this->repository);

                        //validate this username has the accepted characters
                        $this->validateUsername($user, $value, $this->repository);

                        break;
                }

            }

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     * @param $username
     * @param $repository
     *
     * @return bool
     * @throws \Exception
     */
    public function validateUsernameUnique(User $user, $username, $repository)
    {
        try {

            $usernameUser = $repository->findOneBy(
                [
                    'username' => $username,
                ]
            );

            if ($usernameUser instanceof User) {
                if ($usernameUser->getId() !== $user->getId()) {
                    throw new UsernameNotUniqueException(
                        sprintf(
                            'Username %s is already in use by another User!',
                            $username
                        )
                    );
                }
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     * @param $username
     * @param $repository
     *
     * @throws \Exception
     */
    public function validateUsername(User $user, $username, $repository)
    {
        try {

            if (!preg_match('/^[a-zA-Z0-9-_]+$/', $username)) {
                throw new InvalidUsernameException('Username must be alphanumeric with dashes or underscores only!');
            }

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     * @param $email
     * @param $repository
     *
     * @return bool
     * @throws \Exception
     */
    public function validateEmail(User $user, $email, $repository)
    {
        try {

            $emailUser = $repository->findOneBy(
                [
                    'email' => $email,
                ]
            );

            if ($emailUser instanceof User) {
                if ($emailUser->getId() !== $user->getId()) {
                    throw new EmailNotUniqueException(
                        sprintf(
                            'Email %s is already in use by another User!',
                            $email
                        )
                    );
                }
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     * @param \Tavro\Bundle\CoreBundle\Entity\Role $thisRole
     *
     * @throws \Exception
     * @return bool
     */
    public function hasRole(User $user, Role $thisRole)
    {
        try {
            $roles = $user->getRoles();

            if (empty($roles)) {
                return false;
            }

            foreach ($roles as $role) {
                if ($role->getId() == $thisRole->getId()) {
                    return true;
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     * @param $roleName
     *
     * @return bool
     * @throws \Exception
     */
    public function hasRoleByName(User $user, $roleName)
    {
        try {
            $role = $this->findRoleByName($roleName);
        } catch (\Exception $e) {
            throw $e;
        }

        return $this->hasRole($user, $role);
    }

    /**
     * If a User has forgotten their password, set a password token and a time by
     * which that token will expire to give them an opportunity to reset their password.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     *
     * @throws \Exception
     */
    public function forgotPassword(Request $request, User $user)
    {
        try {

            $dt = new \DateTime();
            $dt->modify("+30 minutes");

            $parameters = [
                'password_token_expire' => $dt,
                'password_token' => Uuid::uuid4()
            ];

            $this->patch($request, $user, $parameters);

        }
        catch (\Exception $e) {
            throw $e;
        }
    }

}