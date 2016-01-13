<?php

namespace Tavro\Bundle\ApiBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Exception\FormException;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\NotAuthorizedOrganizationException;

use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\ApiBundle\Services\EntityHandler;
use Tavro\Bundle\ApiBundle\Model\OrganizationHandlerInterface;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;
use Tavro\Bundle\ApiBundle\Exception\ApiException;
use Tavro\Bundle\ApiBundle\Exception\ApiNotFoundException;
use Tavro\Bundle\ApiBundle\Exception\ApiRequestLimitException;
use Tavro\Bundle\ApiBundle\Exception\ApiAccessDeniedException;
use Tavro\Bundle\ApiBundle\Exception\ApiRequestSizeException;
use Tavro\Bundle\ApiBundle\Model\OwnershipHandlerInterface;

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