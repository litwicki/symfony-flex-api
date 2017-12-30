<?php

namespace Tavro\Event\User;

use Symfony\Component\EventDispatcher\Event;
use Tavro\Entity\User;

/**
 * Class UserForgotPasswordEvent
 * @package Tavro\Event\User
 */
class UserForgotPasswordEvent extends Event
{
    const NAME = 'user.forgot_password';

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