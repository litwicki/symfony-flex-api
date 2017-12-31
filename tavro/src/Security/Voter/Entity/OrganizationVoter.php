<?php

namespace App\Security\Voter\Entity;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Entity\Organization;
use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use App\Security\Voter\TavroVoter;
use App\Model\EntityInterface\EntityInterface;

class OrganizationVoter extends TavroVoter
{
    // these strings are just invented: you can use anything
    const VIEW = 'view';
    const EDIT = 'edit';
    const CREATE = 'create';
    const PATCH = 'patch';

    /**
     * @param string $attribute
     * @param mixed $subject
     *
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return FALSE
        if (!in_array($attribute, array(self::VIEW, self::EDIT, self::CREATE, self::PATCH))) {
            return FALSE;
        }

        // only vote on Organization objects inside this voter
        if (!$subject instanceof Organization) {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {

        $organization = $subject;

        /**
         * If the User is an Administrator, let them proceed as they desire.
         */
        if ($this->decisionManager->decide($token, array('ROLE_ADMIN'))) {
            return TRUE;
        }

        switch ($attribute) {
            case self::CREATE:
                return $this->canCreate($organization, $token);
            case self::VIEW:
                return $this->canView($organization, $token);
            case self::PATCH:
                return $this->canPatch($organization, $token);
            case self::EDIT:
                return $this->canEdit($organization, $token);
        }

        throw new \LogicException('This code should not be reached!');
    }


    protected function canView(EntityInterface $organization, TokenInterface $token)
    {
        return $this->checkAccount($organization->getAccount(), $token);
    }

    protected function canCreate(EntityInterface $organization, TokenInterface $token)
    {
        return $this->decisionManager->decide($token, array('ROLE_ADMIN'));
    }

    protected function canEdit(EntityInterface $organization, TokenInterface $token)
    {
        return $this->checkAccount($organization->getAccount(), $token);
    }

    protected function canPatch(EntityInterface $organization, TokenInterface $token)
    {
        return $this->checkAccount($organization->getAccount(), $token);
    }

}