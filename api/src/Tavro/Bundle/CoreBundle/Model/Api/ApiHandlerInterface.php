<?php

namespace Tavro\Bundle\CoreBundle\Model\Api;

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface ApiHandlerInterface
 *
 * @package Tavro\Bundle\CoreBundle\Model
 */
Interface ApiHandlerInterface
{
    /**
     * Allow overriding logic for creating an Entity.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $parameters
     *
     * @return mixed
     */
    public function create(Request $request, array $parameters);
}
