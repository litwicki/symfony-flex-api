<?php

namespace Tavro\Bundle\CoreBundle\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Tavro\Bundle\CoreBundle\Entity\Organization;
use Tavro\Bundle\CoreBundle\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;

class TavroVoter extends Voter
{

    // these strings are just invented: you can use anything
    const VIEW = 'view';
    const EDIT = 'edit';
    const CREATE = 'create';
    const PATCH = 'patch';

    protected $decisionManager;

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
        throw new \LogicException('This code should not be reached!');
    }

    private function canView($entity, User $user)
    {
        return true;
    }

    private function canCreate($entity, User $user)
    {
        return true;
    }

    private function canEdit($entity, User $user)
    {
        return true;
    }

    private function canPatch($entity, User $user)
    {
        return true;
    }

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

}
