<?php

namespace Tavro\Event\User;

use Symfony\Component\EventDispatcher\Event;
use Tavro\Entity\User;

/**
 * Class UserSignupEvent
 * @package Tavro\Event\User
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