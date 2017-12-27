<?php namespace Tavro\Bundle\CoreBundle\Repository\Entity;

use Tavro\Bundle\CoreBundle\Repository\TavroRepository;
use Tavro\Bundle\CoreBundle\Entity\Account;

class ShareholderRepository extends TavroRepository
{
    public function findAllByAccount(Account $account, $size, $offset)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT s FROM TavroCoreBundle:Shareholder s WHERE s.account = ?account ORDER BY s.create_date DESC')
            ->setMaxResults($size)
            ->setFirstResult($offset)
            ->setParameter('account', $account->getId())
            ->getResult();
    }
}