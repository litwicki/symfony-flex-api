<?php namespace Tavro\Bundle\CoreBundle\Event\Syndicate;

use Symfony\Component\EventDispatcher\Event;
use Tavro\Bundle\CoreBundle\Entity\Syndicate;
use Tavro\Bundle\CoreBundle\Entity\User;

class SyndicateCreateEvent extends Event
{
    const NAME = 'syndicate.create';

    protected $syndicate;

    public function __construct(Syndicate $syndicate)
    {
        $this->syndicate = $syndicate;
    }

    public function getSyndicate()
    {
        return $this->syndicate;
    }
}