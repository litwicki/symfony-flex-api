<?php namespace Tavro\Bundle\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Tavro\Bundle\CoreBundle\Entity\Account;

class ServiceCategoryRepository extends EntityRepository
{
    public function findAllByAccount(Account $account, $size, $offset)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT s FROM TavroCoreBundle:ServiceCategory s WHERE s.account = ?account ORDER BY s.create_date DESC')
            ->setMaxResults($size)
            ->setFirstResult($offset)
            ->setParameter('account', $account->getId())
            ->getResult();
    }
}