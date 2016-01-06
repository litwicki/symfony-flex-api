<?php

namespace Tavro\Bundle\ApiBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Exception\FormException;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\ApiBundle\Services\EntityHandler;
use Tavro\Bundle\ApiBundle\Model\HandlerInterface;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;
use Tavro\Bundle\ApiBundle\Exception\ApiException;
use Tavro\Bundle\ApiBundle\Exception\ApiNotFoundException;
use Tavro\Bundle\ApiBundle\Exception\ApiRequestLimitException;
use Tavro\Bundle\ApiBundle\Exception\ApiAccessDeniedException;
use Tavro\Bundle\ApiBundle\Exception\ApiRequestSizeException;
use Tavro\Bundle\ApiBundle\Model\OwnershipHandlerInterface;

class OwnershipEntityHandler extends EntityHandler implements OwnershipHandlerInterface
{
    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     *
     * @return mixed|void
     * @throws \Tavro\Bundle\ApiBundle\Exception\ApiException
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