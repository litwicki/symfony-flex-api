<?php

namespace Tavro\Bundle\CoreBundle\Event\Account;

use Symfony\Component\EventDispatcher\Event;
use Tavro\Bundle\CoreBundle\Entity\Account;

/**
 * Class AccountOwnerDeactivateEvent
 * @package Tavro\Bundle\CoreBundle\Event\Account
 */
class AccountOwnerDeactivateEvent extends Event
{
    const NAME = 'account.owner_deactivate';

    protected $account;

    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    public function getAccount()
    {
        return $this->account;
    }
}