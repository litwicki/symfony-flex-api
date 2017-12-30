<?php namespace Tavro\Repository;

use Tavro\Repository\TavroRepositoryInterface;
use Tavro\Entity\Account;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\Query;

interface TavroAccountEntityRepositoryInterface extends TavroRepositoryInterface
{
    /**
     * Get the Count of all Entities within this Account.
     *
     * @param Account $account
     * @param array $options
     * @return mixed
     */
    public function getCountOfAllByAccount(Account $account, array $options = array());

    /**
     * Get the last $num Records from the Database.
     *
     * @param int $num
     *
     * @return array of entities
     */
    public function getLatestByAccount(Account $account, $num = 5);

    /**
     * Get all Entities within this Account.
     *
     * @param Account $account
     * @param $size
     * @param $offset
     * @param array $params
     * @return array
     */
    public function findAllByAccount(Account $account, $size, $offset, array $params = array());
}