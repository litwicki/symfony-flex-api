<?php namespace Tavro\Model\HandlerInterface;

use Tavro\Model\EntityInterface\EntityInterface;
use Tavro\Entity\Account;

interface AccountEntityHandlerInterface
{

    /**
     * Fetch all Entities for a specified Account.
     *
     * @param \Tavro\Entity\Account $account
     *
     * @return array
     */
    public function getAllByAccount(Account $account);

}