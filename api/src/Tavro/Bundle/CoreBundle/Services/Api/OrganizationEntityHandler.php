<?php

namespace Tavro\Bundle\CoreBundle\Services\Api;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\NotAuthorizedOrganizationException;

use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Services\Api\EntityHandler;
use Tavro\Bundle\CoreBundle\Model\Api\OrganizationHandlerInterface;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiNotFoundException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiRequestLimitException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiRequestSizeException;
use Tavro\Bundle\CoreBundle\Model\Api\OwnershipHandlerInterface;

class OrganizationEntityHandler extends EntityHandler implements OrganizationHandlerInterface
{

    public function validate(EntityInterface $entity, array $parameters)
    {
        return $this->validateOrganization();
    }

    public function validateOrganization(EntityInterface $entity, array $parameters)
    {
        if($this->user) {

            if(isset($parameters['organization']) && is_numeric($parameters['organization'])) {

                $organizationId = $parameters['organization'];

                $userOrgs = $this->user->getUserOrganizations();
                foreach($userOrgs as $userOrg) {
                    $organization = $userOrg->getOrganization();
                    if($organization->getId() == $organizationId) {
                        return true;
                    }
                }

            }

        }

        throw new NotAuthorizedOrganizationException('You are not authorized to modify this Organization!');
    }
}