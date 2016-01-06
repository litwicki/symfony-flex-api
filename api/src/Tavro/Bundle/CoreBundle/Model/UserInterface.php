<?php

namespace Tavro\Bundle\CoreBundle\Model;

use Tavro\Bundle\CoreBundle\Model\EntityInterface;

/**
 * Interface UserInterface
 *
 * @package Tavro\Bundle\CoreBundle\Model
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
