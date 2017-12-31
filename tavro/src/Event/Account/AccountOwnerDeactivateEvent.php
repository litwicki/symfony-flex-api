<?php

namespace App\Event\Account;

use Symfony\Component\EventDispatcher\Event;
use App\Entity\Account;

/**
 * Class AccountOwnerDeactivateEvent
 * @package Tavro\Event\Account
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