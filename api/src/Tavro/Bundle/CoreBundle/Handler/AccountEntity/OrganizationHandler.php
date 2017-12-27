<?php

namespace Tavro\Bundle\CoreBundle\Handler\AccountEntity;

use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFieldException;
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

use Tavro\Bundle\CoreBundle\Entity\Organization;
use Tavro\Bundle\CoreBundle\Handler\AccountEntityHandler;
use Tavro\Bundle\CoreBundle\Model\HandlerInterface\AccountEntityHandlerInterface;
use Tavro\Bundle\CoreBundle\Model\HandlerInterface\CommentEntityHandlerInterface;

/**
 * Class OrganizationHandler
 *
 * @package Tavro\Bundle\CoreBundle\Handler\Entity
 */
class OrganizationHandler extends AccountEntityHandler implements CommentEntityHandlerInterface, AccountEntityHandlerInterface
{

    const WEBSITE_REGEX = '_^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:/[^\s]*)?$_iuS';

    /**
     * Additional validation for the Entity.
     *
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface $entity
     * @param array $parameters
     */
    public function validate(EntityInterface $entity, array $parameters = array())
    {

        if(isset($parameters['website'])) {

            if(!preg_match(self::WEBSITE_REGEX, $parameters['website'])) {
                throw new InvalidFormException(sprintf('%s is not a valid website url.', $parameters['website']));
            }

        }

    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface $organization
     *
     * @return array
     * @throws \Exception
     */
    public function getComments(EntityInterface $organization)
    {

        $comments = array();

        if(!$organization instanceof Organization) {
            throw new \Exception(sprintf('Cannot fetch comments for Organization from %s', get_class($organization)));
        }

        $entities = $organization->getOrganizationComments();

        if(!empty($entities)) {
            foreach($entities as $entity) {
                $comments[$entity->getId()] = $entity->getComment();
            }
        }

        return $comments;

    }

}