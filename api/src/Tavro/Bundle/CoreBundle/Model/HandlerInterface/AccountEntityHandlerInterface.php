<?php namespace Tavro\Bundle\CoreBundle\Model\HandlerInterface;

use Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface;
use Tavro\Bundle\CoreBundle\Entity\Account;

interface AccountEntityHandlerInterface
{

    /**
     * Fetch all Entities for a specified Account.
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     *
     * @return array
     */
    public function getAllByAccount(Account $account);

}