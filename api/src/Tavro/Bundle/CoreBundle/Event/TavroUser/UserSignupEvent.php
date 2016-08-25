<?php

namespace Tavro\Bundle\CoreBundle\Event\TavroUser;

use Symfony\Component\EventDispatcher\Event;
use Tavro\Bundle\CoreBundle\Entity\User;

/**
 * Class UserSignupEvent
 * @package Tavro\Bundle\CoreBundle\Event\TavroUser
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