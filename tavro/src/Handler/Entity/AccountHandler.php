<?php

namespace App\Handler\Entity;

use App\Model\EntityInterface\EntityInterface;
use App\Exception\Api\ApiAccessDeniedException;
use App\Exception\Api\ApiException;

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\NodeComment;
use App\Entity\Node;
use App\Entity\Account;
use App\Handler\EntityHandler;

use App\Event\Account\AccountOwnerDeactivateEvent;
use App\Event\Account\AccountDeactivateEvent;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class AccountHandler
 *
 * @package Tavro\Handler\Entity
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