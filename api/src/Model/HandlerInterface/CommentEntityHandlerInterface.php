<?php namespace App\Model\HandlerInterface;

use App\Model\EntityInterface\EntityInterface;

interface CommentEntityHandlerInterface
{

    /**
     * Fetch all Comments from a many:many.
     *
     * @param \App\Model\EntityInterface\EntityInterface $entity
     *
     * @return array
     */
    public function getComments(EntityInterface $entity);

}