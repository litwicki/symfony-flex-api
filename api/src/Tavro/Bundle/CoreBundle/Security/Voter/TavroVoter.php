<?php

namespace Tavro\Bundle\CoreBundle\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

use Tavro\Bundle\CoreBundle\Entity\Account;
use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface;

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

        if ($this->decisionManager->decide($token, array('ROLE_ADMIN'))) {
            return true;
        }

        /**
         * Is there a scenario where we want to allow something to an
         * Anonymous User? Let's just require the User be logged in..
         */
        if(!$token->getUser() instanceof User) {
            return false;
        }

        switch($attribute) {

            case $attribute == self::VIEW:
                return $this->canView($subject, $token);

            case $attribute == self::PATCH:
                return $this->canPatch($subject, $token);

            case $attribute == self::EDIT:
                return $this->canEdit($subject, $token);

            case $attribute == self::CREATE:
                return $this->canCreate($subject, $token);

            case $attribute == self::DELETE:
                return $this->canDelete($subject, $token);

        }
    }

    protected function canView(EntityInterface $entity, TokenInterface $token)
    {
        return TRUE;
    }

    protected function canCreate(EntityInterface $entity, TokenInterface $token)
    {
        return TRUE;
    }

    protected function canEdit(EntityInterface $entity, TokenInterface $token)
    {
        return TRUE;
    }

    protected function canPatch(EntityInterface $entity, TokenInterface $token)
    {
        return TRUE;
    }

    protected function canDelete(EntityInterface $entity, TokenInterface $token)
    {
        return TRUE;
    }

    /**
     * Validate that the entity being manipulated is within the ecosystem
     * of Organizations this User belongs to.
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     *
     * @return bool
     * @throws \Exception
     */
    public function checkAccount(Account $account, TokenInterface $token)
    {
        try {

            $user = $token->getUser();

            if(!$user instanceof User) {
                return false;
            }

            $items = $user->getAccountUsers();
            $accounts = array();

            foreach($items as $item) {
                $acc = $item->getAccount();
                $accounts[$acc->getId()] = $acc;
            }

            $id = $account->getId();

            if(in_array($id, array_keys($accounts))) {
                return true;
            }

            return false;
        }
        catch(\Exception $e) {
            throw $e;
        }

    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\User $newUser
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @return bool
     */
    public function checkUser(User $newUser, TokenInterface $token)
    {
        $user = $token->getUser();

        if(!$user instanceof User) {
            return FALSE;
        }

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
