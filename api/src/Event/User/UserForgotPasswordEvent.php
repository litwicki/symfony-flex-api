<?php

namespace App\Event\User;

use Symfony\Component\EventDispatcher\Event;
use App\Entity\User;

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