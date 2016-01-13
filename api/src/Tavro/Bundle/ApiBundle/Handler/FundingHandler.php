<?php

namespace Tavro\Bundle\ApiBundle\Handler;

use Tavro\Bundle\ApiBundle\Exception\ApiException;
use Tavro\Bundle\ApiBundle\Model\ApiHandlerInterface;
use Tavro\Bundle\ApiBundle\Services\EntityHandler;
use Tavro\Bundle\ApiBundle\Services\OrganizationEntityHandler;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;
use Tavro\Bundle\ApiBundle\Exception\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Entity\Role;
use Tavro\Bundle\CoreBundle\Exception\UsernameNotUniqueException;
use Tavro\Bundle\CoreBundle\Exception\EmailNotUniqueException;
use Tavro\Bundle\CoreBundle\Model\ApiEntityInterface;

use Rhumsaa\Uuid\Uuid;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Tavro\Bundle\CoreBundle\Exception\InvalidUsernameException;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FundingHandler
 *
 * @package Tavro\Bundle\ApiBundle\Handler
 */
class FundingHandler extends OrganizationEntityHandler
{

}