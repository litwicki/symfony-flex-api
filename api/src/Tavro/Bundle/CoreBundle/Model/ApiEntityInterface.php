<?php

namespace Tavro\Bundle\CoreBundle\Model;

/**
 * Interface ApiEntityInterface
 *
 * @package Tavro\Bundle\CoreBundle\Model
 */
interface ApiEntityInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Get Slug (mixed alphanumeric for URLs)
     *
     * @return mixed
     */
    public function getSlug();
}
