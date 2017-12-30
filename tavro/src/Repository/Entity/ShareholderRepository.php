<?php namespace Tavro\Repository\Entity;

use Tavro\Repository\TavroRepository;
use Tavro\Entity\Account;

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