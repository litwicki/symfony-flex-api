<?php

namespace Tavro\Event\User;

use Symfony\Component\EventDispatcher\Event;
use Tavro\Entity\User;

/**
 * Class UserPasswordChangeEvent
 * @package Tavro\Event\User
 */
class UserPasswordChangeEvent extends Event
{
    const NAME = 'user.password_change';

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