<?php

namespace App\Model\EntityInterface;

use App\Model\EntityInterface\EntityInterface;

/**
 * Interface UserInterface
 *
 * @package Tavro\Model
 */
interface UserInterface extends EntityInterface, \Symfony\Component\Security\Core\User\UserInterface
{

    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

}
