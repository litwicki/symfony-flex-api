<?php

namespace Tavro\Bundle\CoreBundle\Handler;

use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Model\EntityHandler;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\UsernameNotUniqueException;
use Tavro\Bundle\CoreBundle\Exception\EmailNotUniqueException;

use Rhumsaa\Uuid\Uuid;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Tavro\Bundle\CoreBundle\Exception\InvalidUsernameException;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class NodeCommentHandler
 *
 * @package Tavro\Bundle\CoreBundle\Handler
 */
class NodeCommentHandler extends EntityHandler
{

    const ACCESS_DENIED_MESSAGE = 'You are not authorized to comment on this Node!';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $parameters
     *
     * @return object|\Tavro\Bundle\CoreBundle\Model\EntityInterface|void
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