<?php namespace Tavro\Event\Syndicate;

use Symfony\Component\EventDispatcher\Event;
use Tavro\Entity\Syndicate;
use Tavro\Entity\User;

class SyndicateDeleteEvent extends Event
{
    const NAME = 'syndicate.delete';

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