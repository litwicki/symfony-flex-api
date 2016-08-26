<?php

namespace Tavro\Bundle\CoreBundle\Security\Voter\Entity;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Tavro\Bundle\CoreBundle\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Tavro\Bundle\CoreBundle\Security\Voter\TavroVoter;

class UserVoter extends TavroVoter
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

        // only vote on User objects inside this voter
        if (!$subject instanceof User) {
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

        /**
         * If the User is an Administrator, let them proceed as they desire.
         */
        if ($this->decisionManager->decide($token, array('ROLE_ADMIN'))) {
            return TRUE;
        }

        switch ($attribute) {
            case self::CREATE:
                return $this->canCreate($subject, $user);
            case self::VIEW:
                return $this->canView($subject, $user);
            case self::PATCH:
                return $this->canPatch($subject, $user);
            case self::EDIT:
                return $this->canEdit($subject, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\User $newUser
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @return bool
     */
    private function canView(User $newUser, TokenInterface $token)
    {
        return $this->checkUser($newUser, $token);
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\User $newUser
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @return bool
     */
    private function canCreate(User $newUser, TokenInterface $token)
    {
        return TRUE;
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\User $newUser
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @return bool
     */
    private function canEdit(User $newUser, TokenInterface $token)
    {
        return $this->checkUser($newUser, $token);
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\User $newUser
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @return bool
     */
    private function canPatch(User $newUser, TokenInterface $token)
    {
        return $this->checkUser($newUser, $token);
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\User $newUser
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @return bool
     */
    public function checkUser(User $newUser, TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, array('ROLE_ADMIN'))) {
            return TRUE;
        }
        elseif($token->getUser()->getId() === $newUser->getId()) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

}