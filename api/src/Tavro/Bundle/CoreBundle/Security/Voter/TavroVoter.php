<?php

namespace Tavro\Bundle\CoreBundle\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

use Tavro\Bundle\CoreBundle\Entity\Account;
use Tavro\Bundle\CoreBundle\Entity\User;

class TavroVoter extends Voter
{

    // these strings are just invented: you can use anything
    const VIEW = 'view';
    const EDIT = 'edit';
    const CREATE = 'create';
    const PATCH = 'patch';
    const DELETE = 'delete';

    protected $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return FALSE
        if (!in_array($attribute, array(self::VIEW, self::EDIT, self::CREATE, self::PATCH, self::DELETE))) {
            return FALSE;
        }

        return TRUE;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {

        $user = $token->getUser();

        switch($attribute) {

            case $attribute == self::VIEW:
                return $this->canView($subject, $user);

            case $attribute == self::PATCH:
                return $this->canPath($subject, $user);

            case $attribute == self::EDIT:
                return $this->canEdit($subject, $user);

            case $attribute == self::CREATE:
                return $this->canCreate($subject, $user);

            case $attribute == self::DELETE:
                return $this->canDelete($subject, $user);

        }
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

    private function canDelete($entity, User $user)
    {
        return TRUE;
    }

    /**
     * Validate that the entity being manipulated is within the ecosystem
     * of Organizations this User belongs to.
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     *
     * @return bool
     */
    public function checkAccount(Account $account, User $user)
    {
        $items = $user->getAccountUsers();
        $accounts = array();

        foreach($items as $item) {
            $acc = $item->getAccount();
            $accounts[$acc->getId()] = $acc;
        }

        $id = $account->getId();

        if(in_array($id, array_keys($accounts))) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\User $newUser
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @return bool
     */
    public function checkUser(User $newUser, TokenInterface $token)
    {
        $user = $token->getUser();

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
