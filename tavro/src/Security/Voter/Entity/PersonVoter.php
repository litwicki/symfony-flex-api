<?php

namespace App\Security\Voter\Entity;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Entity\User;
use App\Entity\Person;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use App\Security\Voter\TavroVoter;
use App\Model\EntityInterface\EntityInterface;

class PersonVoter extends TavroVoter
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

        // only vote on Person objects inside this voter
        if (!$subject instanceof Person) {
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
        return true;

        /**
         * If the User is an Administrator, let them proceed as they desire.
         */
        if ($this->decisionManager->decide($token, array('ROLE_ADMIN'))) {
            return TRUE;
        }

        switch ($attribute) {
            case self::CREATE:
                return $this->canCreate($subject, $token);
            case self::VIEW:
                return $this->canView($subject, $token);
            case self::PATCH:
                return $this->canPatch($subject, $token);
            case self::EDIT:
                return $this->canEdit($subject, $token);
        }

        throw new \LogicException('This code should not be reached!');
    }

    protected function canView(EntityInterface $person, TokenInterface $token)
    {
        return $this->checkUser($person->getUser(), $token);
    }

    protected function canEdit(EntityInterface $person, TokenInterface $token)
    {
        return $this->checkUser($person->getUser(), $token);
    }

}