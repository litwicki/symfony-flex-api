<?php

namespace App\Security\Voter\Entity;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Model\EntityInterface\EntityInterface;
use App\Entity\User;
use App\Entity\Account;

use App\Exception\Entity\Account\AccountStatusDisabledException;
use App\Exception\Entity\Account\AccountStatusPendingException;
use App\Exception\Entity\Account\AccountStatusOtherException;
use App\Exception\Entity\Account\AccountStatusInvalidException;

use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use App\Security\Voter\TavroVoter;

class AccountVoter extends TavroVoter
{
    protected function canView(EntityInterface $entity, TokenInterface $token)
    {
        switch($entity->getStatus()) {

            case $entity::STATUS_ENABLED:
                return true;

            case $entity::STATUS_DISABLED:
                //throw new AccountStatusDisabledException(sprintf('Account %s is not accessible because it has been disabled.', $entity->__toString()));
                return false;

            case $entity::STATUS_PENDING:
                //throw new AccountStatusPendingException(sprintf('Account %s is not accessible because it is pending.', $entity->__toString()));
                return true;

            case $entity::STATUS_OTHER:
                //throw new AccountStatusOtherException(sprintf('Account %s is not accessible.', $entity->__toString()));
                return true;

            default:
                //throw new AccountStatusInvalidException(sprintf('Account %s could not be loaded with status %s', $entity->__toString(), $entity->getStatus()));
                return false;

        }
    }

    protected function canEdit(EntityInterface $entity, TokenInterface $token)
    {
        return $entity->getUser()->getId() == $token->getUser()->getId();
    }

    protected function canPatch(EntityInterface $entity, TokenInterface $token)
    {
        return $entity->getUser()->getId() == $token->getUser()->getId();
    }

    protected function canDelete(EntityInterface $entity, TokenInterface $token)
    {
        if($entity->getUser()->getId() == $token->getUser()->getId()) {
            return true;
        }

        if ($this->decisionManager->decide($token, array('ROLE_ADMIN', 'ROLE_TAVRO'))) {
            return true;
        }
    }
}