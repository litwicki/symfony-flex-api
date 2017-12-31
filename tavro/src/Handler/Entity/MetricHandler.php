<?php

namespace App\Handler\Entity;

use App\Model\EntityInterface\EntityInterface;
use App\Exception\Api\ApiAccessDeniedException;
use App\Exception\Api\ApiException;

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\NodeComment;
use App\Entity\Node;
use App\Handler\EntityHandler;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class MetricHandler
 *
 * @package Tavro\Handler\Entity
 */
class MetricHandler extends EntityHandler
{

}