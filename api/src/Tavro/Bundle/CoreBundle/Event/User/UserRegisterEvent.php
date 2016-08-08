<?php namespace Tavro\Bundle\CoreBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Tavro\Bundle\CoreBundle\Entity\User;

class UserRegisterEvent extends Event
{
    const NAME = 'tavro.user_registered';

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