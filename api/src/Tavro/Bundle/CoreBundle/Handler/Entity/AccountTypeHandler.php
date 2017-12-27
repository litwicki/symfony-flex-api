<?php

namespace Tavro\Bundle\CoreBundle\Handler\Entity;

use Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;

use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Entity\Comment;
use Tavro\Bundle\CoreBundle\Entity\NodeComment;
use Tavro\Bundle\CoreBundle\Entity\Node;
use Tavro\Bundle\CoreBundle\Entity\Account;
use Tavro\Bundle\CoreBundle\Handler\EntityHandler;

use Tavro\Bundle\CoreBundle\Event\Account\AccountOwnerDeactivateEvent;
use Tavro\Bundle\CoreBundle\Event\Account\AccountDeactivateEvent;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class AccountHandler
 *
 * @package Tavro\Bundle\CoreBundle\Handler\Entity
 */
class AccountTypeHandler extends EntityHandler
{
    
}