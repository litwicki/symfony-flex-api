<?php namespace App\Model\HandlerInterface;

use App\Model\EntityInterface\EntityInterface;
use App\Entity\Account;

interface AccountEntityHandlerInterface
{

    /**
     * Fetch all Entities for a specified Account.
     *
     * @param \App\Entity\Account $account
     *
     * @return array
     */
    public function getAllByAccount(Account $account);

}