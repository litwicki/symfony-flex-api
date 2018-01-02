<?php

namespace App\Handler\Entity;

use App\Exception\Api\ApiException;
use App\Handler\EntityHandler;
use App\Exception\Form\InvalidFormException;
use App\Model\EntityInterface\EntityInterface;
use App\Exception\Api\ApiAccessDeniedException;
use App\Exception\UsernameNotUniqueException;
use App\Exception\EmailNotUniqueException;

use Rhumsaa\Uuid\Uuid;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use App\Exception\InvalidUsernameException;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use App\Component\Form\FormErrors;


/**
 * Class PersonHandler
 *
 * @package Tavro\Handler\Entity
 */
class PersonHandler extends EntityHandler
{
    const ACCESS_DENIED_MESSAGE = 'You are not authorized to manage this Person.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Model\EntityInterface\EntityInterface $entity
     * @param array $parameters
     * @param string $method
     *
     * @throws \Doctrine\DBAL\Exception\NotNullConstraintViolationException
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

                        /**
                         * Allow anyone to create a Person any time.
                         *
                         * $this->auth->isGranted('create', $entity);
                         *
                         */

                        break;

                    case 'PUT':
                        $this->auth->isGranted('edit', $entity);
                        break;

                    case 'PATCH':

                        /**
                         * @TODO: we need to fix this for anonymous Users vs. authenticated Users.
                         *
                         * $this->auth->isGranted('patch', $entity);
                         *
                         */

                        break;

                    case 'DELETE':
                        $this->auth->isGranted('delete', $entity);
                        break;

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
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Model\EntityInterface\EntityInterface $entity
     * @param array $parameters
     *
     * @return \Tavro\Model\EntityInterface\EntityInterface
     * @throws \Exception
     */
    public function patch(Request $request, EntityInterface $entity, array $parameters)
    {
        try {

            /**
             * @TODO: Change how this is handled between Anonymous and Authorized Users.
             *
             * $this->auth->isGranted('patch', $entity);
             */

            return $this->applyPatch($request, $entity, $parameters);

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

}