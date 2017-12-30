<?php

namespace Tavro\Handler\Entity;

use Tavro\Model\EntityInterface\EntityInterface;
use Tavro\Exception\Api\ApiAccessDeniedException;
use Tavro\Exception\Api\ApiException;

use Tavro\Entity\User;
use Tavro\Entity\Comment;
use Tavro\Entity\NodeComment;
use Tavro\Entity\Node;
use Tavro\Handler\EntityHandler;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class DashboardHandler
 *
 * @package Tavro\Handler\Entity
 */
class DashboardHandler extends EntityHandler
{

}