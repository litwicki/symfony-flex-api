<?php

namespace Tavro\Bundle\CoreBundle\Handler\Entity;

use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Handler\EntityHandler;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;
use Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\UsernameNotUniqueException;
use Tavro\Bundle\CoreBundle\Exception\EmailNotUniqueException;

use Rhumsaa\Uuid\Uuid;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Tavro\Bundle\CoreBundle\Exception\InvalidUsernameException;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RoleHandler
 *
 * @package Tavro\Bundle\CoreBundle\Handler\Entity
 */
class RoleHandler extends EntityHandler
{
    /**
     * List All Roles for current User.
     *
     * @param array|null $params
     *
     * @return array
     */
    public function findAll(array $params = null)
    {
        try {

            return $this->getRepository()->findAll();

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

}