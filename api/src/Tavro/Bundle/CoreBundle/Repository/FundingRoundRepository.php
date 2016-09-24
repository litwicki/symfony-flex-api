<?php namespace Tavro\Bundle\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Tavro\Bundle\CoreBundle\Entity\Account;

class FundingRoundRepository extends EntityRepository
{
    public function findAllByAccount(Account $account, $size, $offset)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT f FROM TavroCoreBundle:FundingRound f WHERE f.account = ?account ORDER BY f.id DESC')
            ->setMaxResults($size)
            ->setFirstResult($offset)
            ->setParameter('account', $account->getId())
            ->getResult();
    }
}