<?php

namespace Tavro\Handler\AccountEntity;

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

use Tavro\Handler\AccountEntityHandler;
use Tavro\Model\HandlerInterface\AccountEntityHandlerInterface;

/**
 * Class ProductHandler
 *
 * @package Tavro\Handler\Entity
 */
class ProductHandler extends AccountEntityHandler implements AccountEntityHandlerInterface
{

}