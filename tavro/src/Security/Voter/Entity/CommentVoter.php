<?php

namespace Tavro\Security\Voter\Entity;

use Symfony\Component\CssSelector\Parser\Token;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Tavro\Entity\User;
use Tavro\Entity\Comment;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Tavro\Security\Voter\TavroVoter;
use Tavro\Model\EntityInterface\EntityInterface;

class CommentVoter extends TavroVoter
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

        // only vote on Comment objects inside this voter
        if (!$subject instanceof Comment) {
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

    /**
     * We need to verify that the current User has access to the
     * parent object for this Comment to ensure they have access to create
     * the Comment in the first place.
     *
     * @param \Tavro\Model\EntityInterface\EntityInterface $comment
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     *
     * @return bool
     */
    protected function canCreate(EntityInterface $comment, TokenInterface $token)
    {
        return $this->checkComment($comment, $token);
    }

    protected function canEdit(EntityInterface $comment, TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, array('ROLE_ADMIN'))) {
            return TRUE;
        }
        elseif($comment->getUser()->getId() === $token->getUser()->getId()) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    /**
     * @param \Tavro\Model\EntityInterface\EntityInterface $comment
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     *
     * @return bool
     */
    protected function canPatch(EntityInterface $comment, TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, array('ROLE_ADMIN'))) {
            return TRUE;
        }
        elseif($comment->getUser()->getId() === $token->getUser()->getId()) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    /**
     * @param \Tavro\Model\EntityInterface\EntityInterface $comment
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @return bool
     */
    private function checkComment(Comment $comment, TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, array('ROLE_ADMIN'))) {
            return TRUE;
        }

        $userOrganizations = $token->getUser()->getOrganizations();
        if($userOrganizations->isEmpty()) {
            return FALSE;
        }

        /**
         * Is this a Node Comment?
         */
        $nodeComments = $comment->getNodeComments();
        if($nodeComments->isEmpty()) {
            foreach($nodeComments as $nc) {
                if($this->checkAccount($nc->getNode()->getAccount(), $token->getUser())) {
                    return TRUE;
                }
            }
        }

        /**
         * Is this an Expense Comment?
         */
        $expenseComments = $comment->getExpenseComments();
        if($expenseComments->isEmpty()) {
            foreach($nodeComments as $nc) {
                if($this->checkAccount($nc->getExpense()->getAccount(), $token->getUser())) {
                    return TRUE;
                }
            }
        }

        /**
         * Is this a Revenue Comment?
         */
        $revenueComments = $comment->getRevenueComments();
        if($revenueComments->isEmpty()) {
            foreach($revenueComments as $rc) {
                foreach($nodeComments as $nc) {
                    if($this->checkAccount($nc->getRevenue()->getAccount(), $token->getUser())) {
                        return TRUE;
                    }
                }
            }
        }

        /**
         * Is this an Organization Comment?
         */
        $orgComments = $comment->getOrganizationComments();
        if($orgComments->isEmpty()) {
            foreach($orgComments as $nc) {
                if($this->checkAccount($nc->getOrganization()->getAccount(), $token->getUser())) {
                    return TRUE;
                }
            }
        }

        /**
         * Is this a FundingRound Comment?
         */
        $fundingRoundComments = $comment->getFundingRoundComments();
        if($fundingRoundComments->isEmpty()) {
            foreach($fundingRoundComments as $nc) {
                if($this->checkAccount($nc->getFundingRound()->getOrganization(), $token->getUser())) {
                    return TRUE;
                }
            }
        }

        /**
         * Whew... valiant effort but you sir are a tricksy
         * Hobbit, and we cannot let the Hobbitses comment
         * without having permission!
         */
        return FALSE;
    }

}