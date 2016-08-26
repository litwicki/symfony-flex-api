<?php

namespace Tavro\Bundle\CoreBundle\Security\Voter\Entity;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Tavro\Bundle\CoreBundle\Entity\Organization;
use Tavro\Bundle\CoreBundle\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Tavro\Bundle\CoreBundle\Security\Voter\TavroVoter;

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
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return FALSE;
        }

        $organization = $subject;

        /**
         * If the User is an Administrator, let them proceed as they desire.
         */
        if ($this->decisionManager->decide($token, array('ROLE_ADMIN'))) {
            return TRUE;
        }

        switch ($attribute) {
            case self::CREATE:
                return $this->canCreate($organization, $user);
            case self::VIEW:
                return $this->canView($organization, $user);
            case self::PATCH:
                return $this->canPatch($organization, $user);
            case self::EDIT:
                return $this->canEdit($organization, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     *
     * @return bool
     */
    private function canView(Organization $organization, User $user)
    {
        return $this->checkOrganization($organization, $user);
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     *
     * @return bool
     */
    private function canCreate(Organization $organization, User $user)
    {
        /**
         * For now.... only Admins can create new Organizations.
         */
        return $user->isAdmin();
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     *
     * @return bool
     */
    private function canEdit(Organization $organization, User $user)
    {
        return $this->checkOrganization($organization, $user);
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     *
     * @return bool
     */
    private function canPatch(Organization $organization, User $user)
    {
        return $this->checkOrganization($organization, $user);
    }

}