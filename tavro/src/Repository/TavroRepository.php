<?php namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\Query;

class TavroRepository extends EntityRepository
{

    /**
     * Our new getAllPosts() method
     *
     * 1. Create & pass query to paginate method
     * 2. Paginate will return a `\Doctrine\ORM\Tools\Pagination\Paginator` object
     * 3. Return that object to the controller
     *
     * @param integer $page The current page (passed from controller)
     *
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function getAll($page = 1, $limit = 5)
    {
        // Create our query
        $query = $this->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->getQuery();

        // No need to manually get get the result ($query->getResult())

        $paginator = $this->paginate($query, $page, $limit);

        return $paginator;
    }

    /**
     * Get the Count of *all* entities.
     *
     * @TODO: consider the performance of this as the table grows!
     *
     * @param array $options
     * @return mixed
     */
    public function getCountOfAll(array $options = array())
    {
        $query = $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Paginator Helper
     *
     * Pass through a query object, current page & limit
     * the offset is calculated from the page and limit
     * returns an `Paginator` instance, which you can call the following on:
     *
     *     $paginator->getIterator()->count() # Total fetched (ie: `5` posts)
     *     $paginator->count() # Count of ALL posts (ie: `20` posts)
     *     $paginator->getIterator() # ArrayIterator
     *
     * @param Doctrine\ORM\Query $dql   DQL Query Object
     * @param integer            $page  Current page (defaults to 1)
     * @param integer            $limit The total number per page (defaults to 5)
     *
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function paginate($dql, $page = 1, $limit = 5)
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1)) // Offset
            ->setMaxResults($limit); // Limit

        return $paginator;
    }

	/**
	 * Get the last $num Records from the Database.
	 *
	 * @param int $num
	 *
	 * @return array of entities
	 */
    public function getLatest($num = 5)
    {
	    $query = $this->createQueryBuilder('a')
		        ->orderBy('a.id', 'DESC')
		        ->setMaxResults($num)
		        ->getQuery();

	    return $query->getResult();
    }

}