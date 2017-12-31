<?php

namespace App\Event\User;

use Symfony\Component\EventDispatcher\Event;
use App\Entity\User;

/**
 * Class UserSignupEvent
 * @package Tavro\Event\User
 */
class UserSignupEvent extends Event
{
    const NAME = 'user.signup';

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