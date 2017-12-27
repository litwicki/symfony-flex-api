<?php

namespace Tavro\Bundle\CoreBundle\Handler\Entity;

use Rhumsaa\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Tavro\Bundle\CoreBundle\Event\User\UserForgotPasswordEvent;
use Tavro\Bundle\CoreBundle\Event\User\UserSignupEvent;
use Tavro\Bundle\CoreBundle\Event\User\UserPasswordChangeEvent;
use Tavro\Bundle\CoreBundle\EventSubscriber\User\UserSubscriber;
use Tavro\Bundle\CoreBundle\Exception\Password\PasswordLengthException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\HttpFoundation\Request;

use Tavro\Bundle\CoreBundle\Exception\InvalidUsernameException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Handler\EntityHandler;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;
use Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\UsernameNotUniqueException;
use Tavro\Bundle\CoreBundle\Exception\EmailNotUniqueException;
use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Entity\Role;
use Tavro\Bundle\CoreBundle\Component\Form\FormErrors;
use Tavro\Bundle\CoreBundle\Exception\Entity\TavroUser\UserNotActivatedException;

/**
 * Class UserHandler
 *
 * @package Tavro\Bundle\CoreBundle\Handler\Entity
 */
class UserHandler extends EntityHandler
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $parameters
     *
     * @return object
     * @throws \Exception
     */
    public function create(Request $request, array $parameters)
    {
        try {

            /**
             * Enforce status on new Users to always be pending.
             */
            $parameters['status'] = self::STATUS_PENDING;

            $roles = (empty($parameters['roles'])) ? ['ROLE_USER'] : $parameters['roles'];
            unset($parameters['roles']);

            $entity = $this->processForm($request, $this->createEntity(), $parameters, self::HTTP_METHOD_POST);

            $this->setUserRoles($entity, $roles);

            $event = new UserSignupEvent($entity);
            $this->dispatcher->dispatch(UserSignupEvent::NAME, $event);

            $this->dispatchCreateEvent($entity);

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
   * Fully update a User
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface $entity
   * @param array $parameters
   *
   * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface
   */
    public function put(Request $request, EntityInterface $entity, array $parameters)
    {
        try {

            $user = $this->processForm($request, $entity, $parameters, self::HTTP_METHOD_PUT);

            if(isset($parameters['roles'])) {
                $this->setUserRoles($user, $parameters['roles']);
            }

            $this->dispatchUpdateEvent($entity);

            return $user;
        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * @param integer $id
     *
     * @return object
     * @throws \Exception
     */
    public function get($id)
    {
        try {

            $entity = $this->getRepository()->find($id);

            if(true === ($entity->getStatus() === $entity::STATUS_PENDING)) {
                throw new UserNotActivatedException(sprintf('User %s must be activated.', $id));
            }

            if(false === ($this->auth->isGranted('view', $entity))) {
                $message = sprintf('You are not authorized to view this %s.', $this->entityClass, $id);
                throw new ApiAccessDeniedException($message);
            }

            return $entity;

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
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface $entity
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

                $this->om->persist($entity);
                $this->om->flush();

                if (isset($parameters['new_password'])) {
                    $this->validateNewPassword($parameters);
                    $password = $this->setPassword($entity, $parameters['password']);
                    $entity->setPassword($password);
                }

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
     * @return array
     */
    public function getAll(array $params = null)
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

            $entities = $this->getRepository()->findBy(
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
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\User
     */
    public function setUserRoles(User $user, array $roles)
    {
        foreach ($roles as $role) {

            if ($role instanceof Role) {
                $user->addRole($role);
            }
            elseif(is_numeric($role)) {
                return $this->setUserRolesById($user, $roles);
            }
            else {
                return $this->setUserRolesByName($user, $roles);
            }

        }

        $this->om->persist($user);
        $this->om->flush();
        return $user;
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
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface $user
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface
     * @throws \Exception
     */
    public function resetApiKey(EntityInterface $user)
    {
        try {

            $user->resetApiKey();
            $this->om->persist($user);
            $this->om->flush();

            return $user;

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface $user
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface
     * @throws \Exception
     */
    public function resetApiPassword(EntityInterface $user)
    {
        try {

            $user->resetApiPassword();
            $this->om->persist($user);
            $this->om->flush();

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
                $entities = $this->getRepository()->findAll();
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
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface
     * @throws \Exception
     */
    public function resetPassword(Request $request, User $user, $parameters = array())
    {
        try {

            $this->validateNewPassword($parameters);

            $user->setPasswordTokenExpire(null);
            $user->setPasswordToken(null);
            $user->setPassword($this->setPassword($user, $parameters['new_password']));
            $this->om->persist($user);
            $this->om->flush();

            $event = new UserPasswordChangeEvent($user);
            $this->dispatcher->dispatch(UserPasswordChangeEvent::NAME, $event);

            $this->dispatchCreateEvent($user);

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     * @param array $parameters
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\User
     * @throws \Exception
     */
    public function changePassword(Request $request, User $user, array $parameters = array())
    {
        try {

            $password = $parameters['password'];

            if(false === ($password == $user->getPassword())) {
                throw new \Exception('You entered an incorrect `current` password!');
            }

            $this->resetPassword($request, $user, $parameters);

            return $user;

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param array $parameters
     *
     * @throws \Exception
     */
    public function validateNewPassword(array $parameters = array())
    {

        if(!isset($parameters['new_password_confirm']) || !isset($parameters['new_password'])) {
            throw new \Exception('Please confirm your new password.');
        }

        if(false === ($parameters['new_password'] === $parameters['new_password_confirm'])) {
            throw new \Exception('Invalid or mismatching passwords.');
        }

    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface $entity
     * @param array $parameters
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface
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

            /**
             * @TODO: when do we actually apply the event here?
             */
            //$this->dispatchUpdateEvent($entity);

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
        if ($this->getRepository()->findOneBy(['email' => $email])) {
            throw new EmailNotUniqueException(
                sprintf(
                    'User with email (%s) already exists!',
                    $email
                )
            );
        }
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface $user
     * @param array $parameters
     *
     * @throws \Exception
     */
    public function validate(EntityInterface $user, array $parameters = [])
    {

        try {

            foreach ($parameters as $key => $value) {

                switch ($key) {

                    case 'email':
                        $this->validateEmail($user, $email = $value, $this->getRepository());
                        break;

                    case 'username':

                        //validate this username isn't in use by another user
                        $this->validateUsernameUnique($user, $username = $value, $this->getRepository());

                        //validate this username has the accepted characters
                        $this->validateUsername($user, $value, $this->getRepository());

                        break;
                }

            }

        }
        catch (\Exception $e) {
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

            $usernameUser = $repository->findOneBy([
                    'username' => $username
            ]);

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
     * If a User has forgotten their password, set a password token and a time
     * by which that token will expire to give them an opportunity to reset
     * their password.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     *
     * @return array
     * @throws \Exception
     */
    public function forgotPassword(Request $request, User $user)
    {
        try {

            $dt = new \DateTime();
            $dt->modify("+30 minutes");

            $token = Uuid::uuid4();

            $user->setPasswordTokenExpire($dt);
            $user->setPasswordToken($token);
            $this->om->persist($user);
            $this->om->flush();

            $event = new UserForgotPasswordEvent($user);
            $this->dispatcher->dispatch(UserForgotPasswordEvent::NAME, $event);

            return [
                'token' => $token->toString(),
                'expires' => $user->getPasswordTokenExpire()
            ];

        }
        catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Set (encode) the User's password.
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     * @param $password
     *
     * @return string
     */
    public function setPassword(User $user, $password)
    {
        return $this->encoderFactory->getEncoder($user)->encodePassword($password, $user->getSalt());
    }

}