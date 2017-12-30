<?php

namespace Tavro\Event\Account;

use Symfony\Component\EventDispatcher\Event;
use Tavro\Entity\Account;

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