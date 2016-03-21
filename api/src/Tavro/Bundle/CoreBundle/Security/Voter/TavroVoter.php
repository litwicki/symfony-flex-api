<?php

namespace Tavro\Bundle\CoreBundle\Security\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Entity\Organization;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;

/**
 * Class VariableVoter
 *
 * @package Tavro\Bundle\CoreBundle\Voter
 */
class TavroVoter implements VoterInterface
{
    /**
     * Validate that the entity being manipulated is within the ecosystem
     * of Organizations this User belongs to.
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     *
     * @return bool
     */
    public function checkOrganization(Organization $organization, User $user)
    {
        $items = $user->getUserOrganizations();
        $organizations = array();

        foreach($items as $item) {
            $org = $item->getOrganization();
            $organizations[$org->getId()] = $org;
        }

        $id = $organization->getId();

        if(in_array($id, array_keys($organizations))) {
            return true;
        }

        return false;
    }

    const CREATE = 'create';
    const VIEW = 'view';
    const EDIT = 'edit';
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
        return in_array($attribute, array(self::CREATE, self::VIEW, self::EDIT, self::DELETE));
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
        return $class instanceof EntityInterface;
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
        return VoterInterface::ACCESS_ABSTAIN;
    }

}
