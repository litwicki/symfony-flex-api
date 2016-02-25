<?php namespace Tavro\Bundle\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{

    public function findAllNonAdmin()
    {
        return $this->getEntityManager()
                    ->createQuery(
                        "SELECT u FROM TavroCoreBundle:User u JOIN u.roles r WHERE r.role NOT IN('ROLE_ADMIN')"
                    )
                    ->getResult();
    }

}