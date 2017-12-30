<?php

namespace Tavro\Handler\Entity;

use Tavro\Model\EntityInterface\EntityInterface;
use Tavro\Exception\Api\ApiAccessDeniedException;
use Tavro\Exception\Api\ApiException;

use Tavro\Entity\User;
use Tavro\Entity\Comment;
use Tavro\Entity\NodeComment;
use Tavro\Entity\Node;
use Tavro\Entity\Account;
use Tavro\Handler\EntityHandler;

use Tavro\Event\Account\AccountOwnerDeactivateEvent;
use Tavro\Event\Account\AccountDeactivateEvent;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class AccountHandler
 *
 * @package Tavro\Handler\Entity
 */
class AccountTypeHandler extends EntityHandler
{
    
}