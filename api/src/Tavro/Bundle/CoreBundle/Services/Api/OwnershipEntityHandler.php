<?php

namespace Tavro\Bundle\CoreBundle\Services\Api;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Services\Api\EntityHandler;
use Tavro\Bundle\CoreBundle\Model\Api\HandlerInterface;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiNotFoundException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiRequestLimitException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiRequestSizeException;
use Tavro\Bundle\CoreBundle\Model\Api\OwnershipHandlerInterface;

class OwnershipEntityHandler extends EntityHandler implements OwnershipHandlerInterface
{
    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     *
     * @return mixed|void
     * @throws \Tavro\Bundle\CoreBundle\Exception\ApiException
     */
    public function findByUser(User $user = null)
    {
        try {

            $entities = array();

            if(is_null($user)) {
                return $entities;
            }

            return $this->repository->findBy(array(
                'user' => $user
            ));

        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }
}