<?php namespace Tavro\Model\HandlerInterface;

use Tavro\Model\EntityInterface\EntityInterface;

interface CommentEntityHandlerInterface
{

    /**
     * Fetch all Comments from a many:many.
     *
     * @param \Tavro\Model\EntityInterface\EntityInterface $entity
     *
     * @return array
     */
    public function getComments(EntityInterface $entity);

}