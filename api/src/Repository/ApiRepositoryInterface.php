<?php namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\Query;

interface EntityRepositoryInterface
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
    public function getAll($page, $limit);

    /**
     * Get the Count of *all* entities.
     *
     * @TODO: consider the performance of this as the table grows!
     *
     * @param array $options
     * @return mixed
     */
    public function getCountOfAll(array $options = array());

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
    public function paginate($dql, $page, $limit);

    /**
     * Get the last $num Records from the Database.
     *
     * @param int $num
     *
     * @return array of entities
     */
    public function getLatest($num);

}