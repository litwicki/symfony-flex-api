<?php

namespace Tavro\Model\EntityInterface;

use Tavro\Model\EntityInterface\EntityInterface;

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
