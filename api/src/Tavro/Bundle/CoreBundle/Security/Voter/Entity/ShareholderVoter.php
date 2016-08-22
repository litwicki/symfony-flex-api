<?php

namespace Tavro\Bundle\CoreBundle\Security\Voter\Entity;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Tavro\Bundle\CoreBundle\Entity\Shareholder;
use Tavro\Bundle\CoreBundle\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Tavro\Bundle\CoreBundle\Security\Voter\TavroVoter;

class ShareholderVoter extends TavroVoter
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
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::VIEW, self::EDIT, self::CREATE, self::PATCH))) {
            return false;
        }

        // only vote on Shareholder objects inside this voter
        if (!$subject instanceof Shareholder) {
            return false;
        }

        return true;
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
            return false;
        }

        $shareholder = $subject;

        /**
         * If the User is an Administrator, let them proceed as they desire.
         */
        if ($this->decisionManager->decide($token, array('ROLE_ADMIN'))) {
            return true;
        }

        switch ($attribute) {
            case self::CREATE:
                return $this->canCreate($shareholder, $user);
            case self::VIEW:
                return $this->canView($shareholder, $user);
            case self::PATCH:
                return $this->canPatch($shareholder, $user);
            case self::EDIT:
                return $this->canEdit($shareholder, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\Shareholder $shareholder
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     *
     * @return bool
     */
    private function canView(Shareholder $shareholder, User $user)
    {
        $frs = $shareholder->getFundingRoundShareholders()->first();
        $organization = $frs->getFundingRound()->getOrganization();
        return $this->checkOrganization($organization, $user);
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\Shareholder $shareholder
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     *
     * @return bool
     */
    private function canCreate(Shareholder $shareholder, User $user)
    {
        /**
         * For now.... only Admins can create new Shareholders.
         */
        return $user->isAdmin();
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\Shareholder $shareholder
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     *
     * @return bool
     */
    private function canEdit(Shareholder $shareholder, User $user)
    {
        return $this->checkShareholder($shareholder, $user);
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\Shareholder $shareholder
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     *
     * @return bool
     */
    private function canPatch(Shareholder $shareholder, User $user)
    {
        return $this->checkShareholder($shareholder, $user);
    }

}