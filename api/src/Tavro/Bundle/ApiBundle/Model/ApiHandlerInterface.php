<?php

namespace Tavro\Bundle\ApiBundle\Model;

/**
 * Interface ApiHandlerInterface
 *
 * @package Tavro\Bundle\ApiBundle\Model
 */
Interface ApiHandlerInterface
{
    /**
     * Allow overriding logic for creating an Entity.
     *
     * @param array $parameters
     *
     * @return mixed
     */
    public function create(array $parameters);
}
