<?php namespace Tavro\Bundle\CoreBundle\Model\HandlerInterface;

use Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface;

interface CommentEntityHandlerInterface
{

    /**
     * Fetch all Comments from a many:many.
     *
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface $entity
     *
     * @return array
     */
    public function getComments(EntityInterface $entity);

}