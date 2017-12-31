<?php

namespace App\Handler\Entity;

use App\Model\EntityInterface\EntityInterface;
use App\Exception\Api\ApiAccessDeniedException;
use App\Exception\Api\ApiException;

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\NodeComment;
use App\Entity\Node;
use App\Entity\Account;
use App\Handler\EntityHandler;

use App\Event\Account\AccountOwnerDeactivateEvent;
use App\Event\Account\AccountDeactivateEvent;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class AccountHandler
 *
 * @package Tavro\Handler\Entity
 */
class AccountTypeHandler extends EntityHandler
{
    
}