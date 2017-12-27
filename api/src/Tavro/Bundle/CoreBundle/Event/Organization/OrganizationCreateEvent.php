<?php namespace Tavro\Bundle\CoreBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Tavro\Bundle\CoreBundle\Entity\Organization;
use Tavro\Bundle\CoreBundle\Entity\User;

class OrganizationCreateEvent extends Event
{
    const NAME = 'organization.create';

    protected $organization;

    public function __construct(Organization $organization)
    {
        $this->organization = $organization;
    }

    public function getOrganization()
    {
        return $this->organization;
    }
}