<?php

namespace Tavro\Bundle\CoreBundle\Security\Voter\Entity;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Tavro\Bundle\CoreBundle\Entity\Variable;
use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Security\Voter\TavroVoter;

/**
 * Class VariableVoter
 *
 * @package Tavro\Bundle\CoreBundle\Voter
 */
class VariableVoter extends TavroVoter implements VoterInterface
{

    /**
     * Allows full access to members belonging to the entity, view access to outside admins.
     *
     * @param User $user
     * @param \Tavro\Bundle\CoreBundle\Entity\Variable $entity
     * @param string  $attribute
     *
     * @throws \Exception
     * @return int
     */
    public function checkAccess($user, Variable $entity, $attribute)
    {
        if($user->isAdmin()) {
            return VoterInterface::ACCESS_GRANTED;
        }

        // Deny all other requests
        return VoterInterface::ACCESS_DENIED;
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
    public function supportsAttribute($attribute) {
        return in_array($attribute, array(self::CREATE, self::VIEW, self::EDIT, self::DELETE));
    }

    /**
     * Returns true if object is an instance of GrowthCase.
     *
     * @param object $class
     *
     * @return bool
     */
    public function supportsClass($class) {
        return $class instanceof Variable;
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
    public function vote(TokenInterface $token, $entity, array $attributes) {
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