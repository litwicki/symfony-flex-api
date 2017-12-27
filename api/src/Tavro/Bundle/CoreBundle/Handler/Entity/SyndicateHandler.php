<?php

namespace Tavro\Bundle\CoreBundle\Handler\Entity;

use Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;

use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Entity\Comment;
use Tavro\Bundle\CoreBundle\Entity\NodeComment;
use Tavro\Bundle\CoreBundle\Entity\Node;
use Tavro\Bundle\CoreBundle\Handler\EntityHandler;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class SyndicateHandler
 *
 * @package Tavro\Bundle\CoreBundle\Handler\Entity
 */
class SyndicateHandler extends EntityHandler
{

}