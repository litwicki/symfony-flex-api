<?php namespace App\Model\HandlerInterface;

use App\Model\EntityInterface\EntityInterface;

interface TagEntityHandlerInterface
{

    /**
     * Fetch all Tags from a many:many.
     *
     * @param \App\Model\EntityInterface\EntityInterface $entity
     *
     * @return array
     */
    public function getTags(EntityInterface $entity);

}