<?php namespace App\Event\Organization;

use Symfony\Component\EventDispatcher\Event;
use App\Entity\Organization;
use App\Entity\User;

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