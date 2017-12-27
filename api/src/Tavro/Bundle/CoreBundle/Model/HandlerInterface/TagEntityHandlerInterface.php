<?php namespace Tavro\Bundle\CoreBundle\Model\HandlerInterface;

use Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface;

interface TagEntityHandlerInterface
{

    /**
     * Fetch all Tags from a many:many.
     *
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface $entity
     *
     * @return array
     */
    public function getTags(EntityInterface $entity);

}