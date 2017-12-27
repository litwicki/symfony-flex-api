<?php

namespace Tavro\Bundle\CoreBundle\Event\Account;

use Symfony\Component\EventDispatcher\Event;
use Tavro\Bundle\CoreBundle\Entity\Account;

/**
 * Class AccountCreateEvent
 * @package Tavro\Bundle\CoreBundle\Event\Account
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