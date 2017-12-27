<?php

namespace Tavro\Bundle\CoreBundle\Event\Account;

use Symfony\Component\EventDispatcher\Event;
use Tavro\Bundle\CoreBundle\Entity\Account;

/**
 * Class AccountDeactivateEvent
 * @package Tavro\Bundle\CoreBundle\Event\Account
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