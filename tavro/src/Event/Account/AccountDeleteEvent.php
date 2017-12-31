<?php

namespace App\Event\Account;

use Symfony\Component\EventDispatcher\Event;
use App\Entity\Account;

/**
 * Class AccountDeleteEvent
 * @package Tavro\Event\Account
 */
class AccountDeleteEvent extends Event
{
    const NAME = 'account.delete';

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