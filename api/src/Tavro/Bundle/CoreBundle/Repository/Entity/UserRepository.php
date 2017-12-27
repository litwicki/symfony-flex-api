<?php namespace Tavro\Bundle\CoreBundle\Repository\Entity;

use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Entity\Account;

use Tavro\Bundle\CoreBundle\Repository\TavroRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\Query;

class UserRepository extends TavroRepository
{

    /**
     * @param $email
     *
     * @return mixed
     * @throws \Exception
     */
	public function findByEmail($email)
	{
		try {

			$em = $this->getEntityManager();

			$query = $em
	            ->createQuery(
	                'SELECT u FROM TavroCoreBundle:User u JOIN TavroCoreBundle:Person p WHERE p.email = :email AND u.person = p.id'
	            )
				->setParameter('email', $email);

			$result = $query->getResult();

			$user = reset($result);

			if(!$user instanceof User) {
				throw new \Exception('Invalid username or password!');
			}

			return $user;

		}
		catch(\Exception $e) {
			throw $e;
		}
	}

    /**
     * Fetch a number of users by Role.
     *
     * @param $role
     * @param null $limit
     *
     * @return array
     * @throws \Exception
     */
	public function findByRole($role, $limit = null)
    {
        try {

            $em = $this->getEntityManager();

            $query = $em
                ->createQuery(
                    'SELECT u FROM TavroCoreBundle:User u JOIN TavroCoreBundle:Role r WHERE r.role=:role'
                )
                ->setParameter('role', $role);

            if(is_numeric($limit)) {
                $query->setMaxResults($limit);
            }

            return $query->getResult();

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param string $role
     *
     * @return array
     * @throws \Exception
     */
    public function findByRoleInAccount(Account $account, $role = 'ROLE_USER')
    {
        try {

            $em = $this->getEntityManager();

            $query = $em
                ->createQuery(
                    'SELECT u FROM TavroCoreBundle:User u JOIN TavroCoreBundle:Role r JOIN TavroCoreBundle:AccountUser au WHERE au.account=:account AND r.role=:role'
                )
                ->setParameter('account', $account->getId())
                ->setParameter('role', $role);

            return $query->getResult();

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

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
                    'SELECT u FROM TavroCoreBundle:User u'
                )
                ->setMaxResults($number);

            return $query->getResult();

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

}