<?php

namespace Tavro\Bundle\CoreBundle\Entity;

use Tavro\Bundle\CoreBundle\Model\EntityInterface;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;

use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Entity\Comment;
use Tavro\Bundle\CoreBundle\Entity\NodeComment;
use Tavro\Bundle\CoreBundle\Entity\Node;
use Tavro\Bundle\CoreBundle\Model\EntityHandler;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class AccountHandler
 *
 * @package Tavro\Bundle\CoreBundle\Handler
 */
class AccountHandler extends EntityHandler
{

}