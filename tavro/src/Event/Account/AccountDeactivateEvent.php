<?php

namespace App\Event\Account;

use Symfony\Component\EventDispatcher\Event;
use App\Entity\Account;

/**
 * Class AccountDeactivateEvent
 * @package Tavro\Event\Account
 */
class AccountDeactivateEvent extends Event
{
    const NAME = 'account.deactivate';

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