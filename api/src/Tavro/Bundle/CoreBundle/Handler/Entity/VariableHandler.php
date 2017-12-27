<?php

namespace Tavro\Bundle\CoreBundle\Handler\Entity;

use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Handler\EntityHandler;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;
use Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface;
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

use Tavro\Bundle\CoreBundle\Handler\AccountEntityHandler;

/**
 * Class VariableHandler
 *
 * @package Tavro\Bundle\CoreBundle\Handler\Entity
 */
class VariableHandler extends AccountEntityHandler
{

}