<?php

namespace Tavro\Bundle\ApiBundle\Model;

use Tavro\Bundle\CoreBundle\Model\EntityInterface;
use Symfony\Component\HttpFoundation\Request;
use Tavro\Bundle\CoreBundle\Entity\User;

Interface HandlerInterface
{

    /**
     * Get a User given the identifier
     *
     * @api
     *
     * @param mixed $id
     *
     * @return EntityInterface
     */
    public function find($id);

    /**
     * Get a list of all Entities.
     *
     * @param array $params
     *
     * @return array
     */
    public function findAll(array $params);

    /**
     * Post User, creates a new User.
     *
     * @api
     *
     * @param array $parameters
     *
     * @return EntityInterface
     */
    public function post(array $parameters);

    /**
     * Edit a User.
     *
     * @api
     *
     * @param EntityInterface   $entity
     * @param array           $parameters
     *
     * @return EntityInterface
     */
    public function put(EntityInterface $entity, array $parameters);

    /**
     * Partially update a User.
     *
     * @api
     *
     * @param EntityInterface   $entity
     * @param array           $parameters
     *
     * @return EntityInterface
     */
    public function patch(EntityInterface $entity, array $parameters);
    
    /**
     * Partially update a User.
     *
     * @api
     *
     * @param EntityInterface   $entity
     *
     * @return EntityInterface
     */
    public function delete(EntityInterface $entity);

    /**
     * Remove an Entity; differs from delete by simply manipulating status.
     *
     * @api
     *
     * @param EntityInterface   $entity
     *
     * @return EntityInterface
     */
    public function remove(EntityInterface $entity);
}
