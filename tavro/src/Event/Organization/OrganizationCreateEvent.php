<?php namespace Tavro\Event;

use Symfony\Component\EventDispatcher\Event;
use Tavro\Entity\Organization;
use Tavro\Entity\User;

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