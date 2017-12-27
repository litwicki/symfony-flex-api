<?php

namespace Tavro\Bundle\CoreBundle\Handler\Entity;

use Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;

use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Entity\Comment;
use Tavro\Bundle\CoreBundle\Entity\NodeComment;
use Tavro\Bundle\CoreBundle\Entity\Node;
use Tavro\Bundle\CoreBundle\Entity\Account;
use Tavro\Bundle\CoreBundle\Handler\EntityHandler;

use Tavro\Bundle\CoreBundle\Event\Account\AccountOwnerDeactivateEvent;
use Tavro\Bundle\CoreBundle\Event\Account\AccountDeactivateEvent;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class AccountHandler
 *
 * @package Tavro\Bundle\CoreBundle\Handler\Entity
 */
class AccountHandler extends EntityHandler
{

    /**
     * Deactivate an Account.
     *
     * @param Account $account
     * @param string $detail
     * @throws \Exception
     */
    public function deactivate(Account $account, $detail = '')
    {

        try {

            if($account->getStatus() == 0) {
                throw new \Exception(sprintf('Account %s is already inactive!', $account->__toString()));
            }

            /**
             * Deactivate the Account.
             */
            $account->setStatus($account::STATUS_DISABLED);
            $this->om->persist($account);
            $this->om->flush();

            if($this->user instanceof User) {
                $owner = ($this->user->getId() == $account->getUser()->getId()) ? true : false;
            }
            else {
                $owner = false;
            }

            /**
             * If the Owner is deactivating the Account, fire a different
             * Event than if we're doing it..
             *
             * Dispatch the Event to handle all the good stuff!
             *
             *  Flush all User Sessions logged in to the Account
             *  Notify the Account Owner
             *  etc..

             */

            if($owner) {
                $event = new AccountOwnerDeactivateEvent($account);
                $this->dispatcher->dispatch(AccountOwnerDeactivateEvent::NAME, $event);
            }
            else {
                $event = new AccountDeactivateEvent($account);
                $this->dispatcher->dispatch(AccountDeactivateEvent::NAME, $event);
            }

        }
        catch(\Exception $e) {
            throw $e;
        }

    }

}