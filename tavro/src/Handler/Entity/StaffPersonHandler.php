<?php

namespace App\Handler\Entity;

use App\Exception\Api\ApiException;
use App\Handler\EntityHandler;
use App\Exception\Form\InvalidFormException;
use App\Model\EntityInterface\EntityInterface;
use App\Exception\Api\ApiAccessDeniedException;
use App\Exception\UsernameNotUniqueException;
use App\Exception\EmailNotUniqueException;

use Rhumsaa\Uuid\Uuid;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use App\Exception\InvalidUsernameException;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Forecast;

use App\Handler\AccountEntityHandler;

/**
 * Class StaffPersonHandler
 *
 * @package Tavro\Handler\Entity
 */
class StaffPersonHandler extends AccountEntityHandler
{

}