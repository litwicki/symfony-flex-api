<?php

namespace Tavro\Bundle\CoreBundle\Security\Voter\Entity;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Tavro\Bundle\CoreBundle\Entity\Organization;
use Tavro\Bundle\CoreBundle\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class OrganizationVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'view';
    const EDIT = 'edit';
    const CREATE = 'create';
    const PATCH = 'patch';

    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::VIEW, self::EDIT, self::CREATE, self::PATCH))) {
            return false;
        }

        // only vote on Organization objects inside this voter
        if (!$subject instanceof Organization) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        $organization = $subject;

        if ($this->decisionManager->decide($token, array('ROLE_ADMIN'))) {
            return true;
        }
        else {

            if($organization->getOwner()->getId() !== $user->getId()) {
                return false;
            }

        }

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($organization, $user);
            case self::EDIT:
                return $this->canEdit($organization, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Organization $organization, User $user)
    {
        return true;
    }

    private function canEdit(Organization $organization, User $user)
    {
        if($organization->getOwner()->getId() === $user->getId()) {
            return true;
        }

        if($user->isAdmin()) {
            return true;
        }
    }

}