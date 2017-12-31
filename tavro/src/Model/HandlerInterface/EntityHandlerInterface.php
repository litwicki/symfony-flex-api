<?php namespace App\Model\HandlerInterface;

use App\Model\EntityInterface\EntityInterface;

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