<?php

namespace App\Handler\AccountEntity;

use App\Model\EntityInterface\EntityInterface;
use App\Exception\Api\ApiAccessDeniedException;
use App\Exception\Api\ApiException;

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\NodeComment;
use App\Entity\Node;
use App\Handler\AccountEntityHandler;

use Symfony\Component\HttpFoundation\Request;
use App\Model\HandlerInterface\AccountEntityHandlerInterface;

/**
 * Class AccountGroupHandler
 *
 * @package Tavro\Handler\Entity
 */
class AccountGroupHandler extends AccountEntityHandler implements AccountEntityHandlerInterface
{

}