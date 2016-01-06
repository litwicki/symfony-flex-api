<?php

namespace Tavro\Bundle\CoreBundle\Model;

/**
 * Interface S3EntityInterface
 *
 * @package Tavro\Bundle\CoreBundle\Model
 */
interface S3EntityInterface
{
    /**
     * Get Key
     *
     * @return string
     */
    public function getAwsKey();

    /**
     * Get Bucket
     *
     * @return string
     */
    public function getBucket();
}
