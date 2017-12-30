<?php namespace Tavro\Model\HandlerInterface;

use Tavro\Model\EntityInterface\EntityInterface;

interface TagEntityHandlerInterface
{

    /**
     * Fetch all Tags from a many:many.
     *
     * @param \Tavro\Model\EntityInterface\EntityInterface $entity
     *
     * @return array
     */
    public function getTags(EntityInterface $entity);

}