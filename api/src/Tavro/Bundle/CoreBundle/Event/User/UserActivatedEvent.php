<?php

namespace Tavro\Bundle\CoreBundle\Event\User;

use Symfony\Component\EventDispatcher\Event;
use Tavro\Bundle\CoreBundle\Entity\User;

/**
 * Class UserSignupEvent
 * @package Tavro\Bundle\CoreBundle\Event\User
 */
class UserActivatedEvent extends Event
{
    const NAME = 'user.activated';

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }
}