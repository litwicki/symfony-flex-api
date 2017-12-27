<?php namespace Tavro\Bundle\CoreBundle\Model\HandlerInterface;

use Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface;

interface EntityHandlerInterface
{

    /**
     * @param integer $id
     *
     * @return object
     * @throws \Exception
     */
    public function get($id);

}