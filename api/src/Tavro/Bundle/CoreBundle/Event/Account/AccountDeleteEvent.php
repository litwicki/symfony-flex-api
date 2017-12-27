<?php

namespace Tavro\Bundle\CoreBundle\Event\Account;

use Symfony\Component\EventDispatcher\Event;
use Tavro\Bundle\CoreBundle\Entity\Account;

/**
 * Class AccountDeleteEvent
 * @package Tavro\Bundle\CoreBundle\Event\Account
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