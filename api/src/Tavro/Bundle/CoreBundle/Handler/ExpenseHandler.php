<?php

namespace Tavro\Bundle\CoreBundle\Handler;

use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Model\Api\ApiHandlerInterface;
use Tavro\Bundle\CoreBundle\Services\Api\EntityHandler;
use Tavro\Bundle\CoreBundle\Services\Api\OrganizationEntityHandler;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Entity\Role;
use Tavro\Bundle\CoreBundle\Exception\UsernameNotUniqueException;
use Tavro\Bundle\CoreBundle\Exception\EmailNotUniqueException;
use Tavro\Bundle\CoreBundle\Model\Api\ApiEntityInterface;
use Tavro\Bundle\CoreBundle\Exception\NotAuthorizedOrganizationException;

use Rhumsaa\Uuid\Uuid;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Tavro\Bundle\CoreBundle\Exception\InvalidUsernameException;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ExpenseHandler
 *
 * @package Tavro\Bundle\CoreBundle\Handler
 */
class ExpenseHandler extends EntityHandler
{

}