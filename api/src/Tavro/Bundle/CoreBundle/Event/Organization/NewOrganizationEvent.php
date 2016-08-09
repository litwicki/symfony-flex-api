<?php namespace Tavro\Bundle\CoreBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Tavro\Bundle\CoreBundle\Entity\Organization;
use Tavro\Bundle\CoreBundle\Entity\User;

class NewOrganizationEvent extends Event
{
    const NAME = 'tavro.new_organization';

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