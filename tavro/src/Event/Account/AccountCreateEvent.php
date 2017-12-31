<?php

namespace App\Event\Account;

use Symfony\Component\EventDispatcher\Event;
use App\Entity\Account;

/**
 * Class AccountCreateEvent
 * @package Tavro\Event\Account
 */
class AccountCreateEvent extends Event
{
    const NAME = 'account.create';

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