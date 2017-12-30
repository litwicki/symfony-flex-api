<?php

namespace Tavro\Handler\AccountEntity;

use Tavro\Model\EntityInterface\EntityInterface;
use Tavro\Exception\Api\ApiAccessDeniedException;
use Tavro\Exception\Api\ApiException;

use Tavro\Entity\User;
use Tavro\Entity\Comment;
use Tavro\Entity\NodeComment;
use Tavro\Entity\Node;
use Tavro\Handler\AccountEntityHandler;

use Symfony\Component\HttpFoundation\Request;
use Tavro\Model\HandlerInterface\AccountEntityHandlerInterface;

/**
 * Class AccountGroupHandler
 *
 * @package Tavro\Handler\Entity
 */
class AccountGroupHandler extends AccountEntityHandler implements AccountEntityHandlerInterface
{

}