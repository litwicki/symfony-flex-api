<?php

namespace App\Handler\Entity;

use App\Exception\Api\ApiException;
use App\Handler\EntityHandler;
use App\Exception\Form\InvalidFormException;
use App\Model\EntityInterface\EntityInterface;
use App\Exception\Api\ApiAccessDeniedException;
use App\Entity\User;
use App\Entity\Role;
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

/**
 * Class OrganizationCommentHandler
 *
 * @package Tavro\Handler\Entity
 */
class OrganizationCommentHandler extends EntityHandler
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $parameters
     *
     * @return \Tavro\Model\EntityInterface\EntityInterface;
     * @throws \Exception
     */
    public function create(Request $request, array $parameters)
    {
        try {

            if(!isset($parameters['status'])) {
                $parameters['status'] = $this::STATUS_ACTIVE;
            }

            $entity = $this->createEntity();
            $comment = $this->processForm($request, $entity, $parameters, $this::HTTP_METHOD_POST);

            return $comment;

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }
}