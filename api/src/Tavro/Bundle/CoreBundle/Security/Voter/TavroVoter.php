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
        // if the attribute isn't one we support, return FALSE
        if (!in_array($attribute, array(self::VIEW, self::EDIT, self::CREATE, self::PATCH))) {
            return FALSE;
        }

        return TRUE;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        throw new \LogicException('This code should not be reached!');
    }

    private function canView($entity, User $user)
    {
        return TRUE;
    }

    private function canCreate($entity, User $user)
    {
        return TRUE;
    }

    private function canEdit($entity, User $user)
    {
        return TRUE;
    }

    private function canPatch($entity, User $user)
    {
        return TRUE;
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
            return TRUE;
        }

        return FALSE;
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\User $newUser
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     *
     * @return bool
     */
    public function checkUser(User $newUser, User $user)
    {
        if($user->isAdmin()) {
            return TRUE;
        }
        elseif($user->getId() === $newUser->getId()) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

}
