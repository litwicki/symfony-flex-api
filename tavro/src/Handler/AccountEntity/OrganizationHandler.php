<?php

namespace Tavro\Handler\AccountEntity;

use Tavro\Exception\Api\ApiException;
use Tavro\Exception\Form\InvalidFieldException;
use Tavro\Handler\EntityHandler;
use Tavro\Exception\Form\InvalidFormException;
use Tavro\Model\EntityInterface\EntityInterface;
use Tavro\Exception\Api\ApiAccessDeniedException;
use Tavro\Exception\UsernameNotUniqueException;
use Tavro\Exception\EmailNotUniqueException;

use Rhumsaa\Uuid\Uuid;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Tavro\Exception\InvalidUsernameException;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\HttpFoundation\Request;

use Tavro\Entity\Organization;
use Tavro\Handler\AccountEntityHandler;
use Tavro\Model\HandlerInterface\AccountEntityHandlerInterface;
use Tavro\Model\HandlerInterface\CommentEntityHandlerInterface;

/**
 * Class OrganizationHandler
 *
 * @package Tavro\Handler\Entity
 */
class OrganizationHandler extends AccountEntityHandler implements CommentEntityHandlerInterface, AccountEntityHandlerInterface
{

    const WEBSITE_REGEX = '_^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:/[^\s]*)?$_iuS';

    /**
     * Additional validation for the Entity.
     *
     * @param \Tavro\Model\EntityInterface\EntityInterface $entity
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
     * @param \Tavro\Model\EntityInterface\EntityInterface $organization
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