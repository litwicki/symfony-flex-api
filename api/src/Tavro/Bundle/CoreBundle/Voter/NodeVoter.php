<?php

namespace Tavro\Bundle\CoreBundle\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;
use Tavro\Bundle\CoreBundle\Entity\Node;
use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Services\Voter\TavroVoter;

/**
 * Class NodeVoter
 *
 * @package Tavro\Bundle\CoreBundle\Voter
 */
class NodeVoter extends TavroVoter  implements VoterInterface
{
    /**
     * Allows full access to members belonging to the entity, view access to outside admins.
     *
     * @param $user
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $entity
     * @param $attribute
     *
     * @return int
     */
    public function checkAccess($user, EntityInterface $entity, $attribute)
    {
        if($user->isAdmin()) {
            return VoterInterface::ACCESS_GRANTED;
        }

        $checkOrganization = $this->checkOrganization($entity->getOrganization(), $user);

        //view a Node
        if($checkOrganization && $attribute == self::VIEW) {
            return VoterInterface::ACCESS_GRANTED;
        }

        //create a Node
        if($checkOrganization && $attribute == self::CREATE) {
            return VoterInterface::ACCESS_GRANTED;
        }

        //patch (edit) a Node
        if($checkOrganization && $attribute == self::PATCH) {
            return VoterInterface::ACCESS_GRANTED;
        }

        if( ($user instanceof User) && ($user->isAdmin() || $entity->getUser()->getId() === $user->getId()) ) {
            return VoterInterface::ACCESS_GRANTED;
        }

        // Deny all other requests
        return VoterInterface::ACCESS_DENIED;
    }

    const CREATE = 'create';
    const VIEW = 'view';
    const EDIT = 'edit';
    const PATCH = 'patch';
    const DELETE = 'delete';

    /**
     * Returns true if the attribute matches known attributes.
     *
     * @param string $attribute
     *
     * @return bool
     */
    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array(self::PATCH, self::CREATE, self::VIEW, self::EDIT, self::DELETE));
    }

    /**
     * Returns true if object is an instance of GrowthCase.
     *
     * @param object $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class instanceof Node;
    }

    /**
     * Returns if the user should have access to the entity.
     *
     * @param TokenInterface $token
     * @param object $entity
     * @param array $attributes
     *
     * @return int
     */
    public function vote(TokenInterface $token, $entity, array $attributes)
    {
        //throw new \Symfony\Component\Security\Acl\Exception\Exception('ERORR');
        //return VoterInterface::ACCESS_GRANTED;
        if (!$this->supportsClass($entity)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        if (1 !== count($attributes)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        $attribute = $attributes[0];

        if(!$this->supportsAttribute($attribute)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        $user = $token->getUser();

        return $this->checkAccess($user, $entity, $attribute);

    }

}
