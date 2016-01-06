<?php

namespace Tavro\Bundle\ApiBundle\Model;

use Tavro\Bundle\CoreBundle\Model\EntityInterface;
use Symfony\Component\HttpFoundation\Request;
use Tavro\Bundle\CoreBundle\Entity\User;

interface OwnershipHandlerInterface
{
    /**
     * Find all Entities owned by a User.
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     *
     * @return mixed
     */
    public function findByUser(User $user = null);
}