<?php namespace Tavro\Bundle\ApiBundle\Security\Jwt;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\DefaultEncoder;

use Tavro\Bundle\ApiBundle\Exception\ApiException;
use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\ApiBundle\Exception\Security\UserStatusNotEnabledException;
use Tavro\Bundle\ApiBundle\Exception\Security\UserStatusPendingException;
use Tavro\Bundle\ApiBundle\Exception\Security\UserStatusDisabledException;

class JwtHandler
{
    protected $encoder;

    public function __construct(DefaultEncoder $encoder)
    {
        $this->encoder = $encoder;
    }

    public function statusCheck(User $user)
    {

        $status = $user->getStatus();

        if(true === ($status == User::STATUS_ENABLED)) {
            return $user;
        }

        /**
         * If we're not "enabled" then let's handle this according to the Status..
         */
        switch($status)
        {
            case ($status == User::STATUS_DISABLED):
                throw new UserStatusDisabledException(sprintf('Your account (%s) is currently `disabled` and not authorized.', $user->__toString()));
            case ($status == User::STATUS_PENDING):
                throw new UserStatusPendingException(sprintf('Your account (%s) is currently `pending` and cannot be authorized.', $user->__toString()));
            case ($status == User::STATUS_OTHER):
                throw new UserStatusNotEnabledException(sprintf('Cannot authorize your account (%s), invalid status.', $user->__toString()));
        }
    }

    /**
     * Create a JWT Token.
     *
     * @param User $user
     * @return string
     */
    public function createToken(User $user)
    {
        try {
            return $this->encoder->encode(['username' => $user->getUsername()]);
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }

    }

}