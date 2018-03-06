<?php namespace App\Repository\Entity;

use App\Entity\User;
use App\Entity\Account;

use App\Repository\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\Query;

class UserRepository extends ApiRepository
{
    /**
     * @param $number
     *
     * @return array
     * @throws \Exception
     */
    public function getNumberOfUsers($number)
    {
        try {

            $em = $this->getEntityManager();

            $query = $em
                ->createQuery(
                    'SELECT u FROM ApiCoreBundle:User u'
                )
                ->setMaxResults($number);

            return $query->getResult();

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

}