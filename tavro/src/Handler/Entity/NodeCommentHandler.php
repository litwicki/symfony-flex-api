<?php

namespace Tavro\Handler\Entity;

use Tavro\Exception\Api\ApiException;
use Tavro\Handler\EntityHandler;
use Tavro\Exception\Form\InvalidFormException;
use Tavro\Model\EntityInterface\EntityInterface;
use Tavro\Exception\Api\ApiAccessDeniedException;
use Tavro\Exception\UsernameNotUniqueException;
use Tavro\Exception\EmailNotUniqueException;

use Rhumsaa\Uuid\Uuid;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Tavro\Exception\InvalidUsernameException;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class NodeCommentHandler
 *
 * @package Tavro\Handler\Entity
 */
class NodeCommentHandler extends EntityHandler
{

    const ACCESS_DENIED_MESSAGE = 'You are not authorized to comment on this Node!';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $parameters
     *
     * @return object|\Tavro\Model\EntityInterface|void
     * @throws \Exception
     */
    public function create(Request $request, array $parameters)
    {
        try {

            if(!isset($parameters['status'])) {
                $parameters['status'] = $this::STATUS_ACTIVE;
            }

            $comment = $this->processForm($request, $this->createEntity(), $parameters, $this::HTTP_METHOD_POST);

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