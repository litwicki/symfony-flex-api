<?php namespace Tavro\Bundle\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Tavro\Bundle\CoreBundle\Entity\Account;

class RevenueCategoryRepository extends EntityRepository
{
    public function findAllByAccount(Account $account, $size, $offset)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT r FROM TavroCoreBundle:RevenueCategory r WHERE r.account = ?account ORDER BY r.body ASC')
            ->setMaxResults($size)
            ->setFirstResult($offset)
            ->setParameter('account', $account->getId())
            ->getResult();
    }
}