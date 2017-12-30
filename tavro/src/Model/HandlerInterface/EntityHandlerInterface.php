<?php namespace Tavro\Model\HandlerInterface;

use Tavro\Model\EntityInterface\EntityInterface;

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