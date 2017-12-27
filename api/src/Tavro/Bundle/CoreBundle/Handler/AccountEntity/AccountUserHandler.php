<?php

namespace Tavro\Bundle\CoreBundle\Handler\AccountEntity;

use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;
use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Entity\Account;
use Tavro\Bundle\CoreBundle\Entity\AccountUser;
use Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Component\Form\FormErrors;

use Tavro\Bundle\CoreBundle\Handler\EntityHandler;

/**
 * Class AccountUserHandler
 *
 * @package Tavro\Bundle\CoreBundle\Handler\Entity
 */
class AccountUserHandler extends EntityHandler
{
    public function getAllByAccount(Account $account, array $params = array())
    {
        try {

            $entities = array();

            foreach($account->getAccountUsers() as $entity) {
                $entities[] = $entity->getUser();
            }

            return [
                'data' => $entities,
                'message' => sprintf('%s Users in Account %s', count($entities), $account->__toString())
            ];

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
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

            $this->validate($entity, $parameters);

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

                switch($method) {

                    case 'POST':
                        $this->auth->isGranted('create', $entity);
                        break;

                    case 'PUT':
                        $this->auth->isGranted('edit', $entity);
                        break;

                    case 'PATCH':
                        $this->auth->isGranted('patch', $entity);
                        break;

                    case 'DELETE':
                        $this->auth->isGranted('delete', $entity);
                        break;

                }

                $this->om->persist($entity);
                $this->om->flush();

                /**
                 * If we're setting $is_primary TRUE on an AccountUser
                 * we need to trigger the reset for all others.
                 */
                if(true === (isset($parameters['is_primary']) && $parameters['is_primary'] == true)) {
                    $this->resetPrimaryAccount($request, $entity);
                }

                return $entity;

            }
            else {
                $formErrors = new FormErrors();
                $errors = $formErrors->getArray($form);
                $exception = $formErrors->getErrorsAsString($errors);
                throw new InvalidFormException($exception, get_class($form));
            }

        }
        catch(NotNullConstraintViolationException $e) {
            throw $e;
        }
        catch(TransformationFailedException $e) {
            throw $e;
        }
        catch(ContextErrorException $e) {
            throw $e;
        }
        catch(UnexpectedTypeException $e) {
            throw $e;
        }
        catch(InvalidPropertyPathException $e) {
            throw $e;
        }
        catch(InvalidFormException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * When we set a primary "AccountUser" we need to reset the Default "Primary" Account
     * for all other AccountUser entities right away.
     *
     * @param Request $request
     * @param AccountUser $accountUser
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function resetPrimaryAccount(Request $request, AccountUser $accountUser)
    {

        /**
         * Get all other AccountUser entities for this User.
         */
        $entities = $accountUser->getUser()->getAccountUsers();

        if(!empty($entities)) {
            foreach($entities as $entity) {
                if(false === ($entity->getId() == $accountUser->getId())) {
                    $this->patch($request, $accountUser, [
                       'is_primary' => false
                    ]);
                }
            }
        }

        //@TODO: Do we want to dispatch an event here for the User to receive an email confirming their new primary account?

        /**
         * Fetch an updated collection to return
         */
        return $accountUser->getUser()->getAccountUsers();

    }

}