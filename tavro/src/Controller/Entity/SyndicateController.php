<?php

namespace App\Controller\Entity;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Exception\Api\ApiException;
use App\Exception\Api\ApiNotFoundException;
use App\Exception\Api\ApiRequestLimitException;
use App\Exception\Api\ApiAccessDeniedException;
use App\Exception\Form\InvalidFormException;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use App\Entity\Node;
use App\Entity\Tag;
use App\Entity\NodeTag;
use App\Entity\NodeComment;
use Symfony\Component\HttpFoundation\Cookie;

use Litwicki\Common\Common;
use App\Controller\Api\EntityApiController;

class SyndicateController extends EntityApiController
{

}