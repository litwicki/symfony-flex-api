<?php namespace Tavro\Bundle\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\Query;
use Tavro\Bundle\CoreBundle\Entity\Account;

class TavroAccountEntityRepository extends EntityRepository
{
    /**
     * Get the Count of all Entities within this Account.
     *
     * @param Account $account
     * @param array $options
     * @return mixed
     */
    public function getCountOfAllByAccount(Account $account, array $options = array())
    {
        $query = $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->where('a.account = :account')
            ->setParameter('account', $account->getId())
            ->getQuery();

        return $query->getSingleScalarResult();
    }

	/**
	 * Get the last $num Records from the Database.
	 *
	 * @param int $num
	 *
	 * @return array of entities
	 */
    public function getLatestByAccount(Account $account, $num = 5)
    {
	    $query = $this->createQueryBuilder('a')
                ->select('a WHERE a.account = :account')
                ->setParameter('account', $account->getId())
		        ->orderBy('a.id', 'DESC')
		        ->setMaxResults($num)
		        ->getQuery();

	    return $query->getResult();
    }

    /**
     * Get all Entities within this Account.
     *
     * @param Account $account
     * @param $size
     * @param $offset
     * @param array $params
     * @return array
     */
    public function findAllByAccount(Account $account, $size, $offset, array $params = array())
    {
        $query = $this->createQueryBuilder('a')
                ->where('a.account = :account')
                ->setParameter('account', $account)
                ->orderBy('a.id', 'DESC')
                ->setFirstResult($offset)
                ->setMaxResults($size)
                ->getQuery();

        return $query->getResult();
    }

}