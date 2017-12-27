<?php

namespace Tavro\Bundle\CoreBundle\Handler\AccountEntity;

use Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;

use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Entity\Comment;
use Tavro\Bundle\CoreBundle\Entity\NodeComment;
use Tavro\Bundle\CoreBundle\Entity\Node;
use Tavro\Bundle\CoreBundle\Handler\AccountEntityHandler;

use Symfony\Component\HttpFoundation\Request;
use Tavro\Bundle\CoreBundle\Model\HandlerInterface\AccountEntityHandlerInterface;

/**
 * Class AccountGroupHandler
 *
 * @package Tavro\Bundle\CoreBundle\Handler\Entity
 */
class AccountGroupHandler extends AccountEntityHandler implements AccountEntityHandlerInterface
{

}