<?php

namespace Tavro\Bundle\CoreBundle\Handler;

use Tavro\Bundle\CoreBundle\Services\Api\EntityHandler;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;

/**
 * Class RoleHandler
 *
 * @package Tavro\Bundle\CoreBundle\Handler
 */
class RoleHandler extends EntityHandler
{
    /**
     * List All Roles for current User.
     *
     * @param array|null $params
     *
     * @return array
     */
    public function findAll(array $params = null)
    {
        try {

            $user = $this->tokenStorage->getToken()->getUser();
            $items = array();

            foreach($user->getRoles() as $role) {
                $items[] = array(
                    'name' => $role->getName(),
                    'role' => $role->getRole()
                );
            }

            return $items;

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

}