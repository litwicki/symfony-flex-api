<?php namespace App\Security\Jwt;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\DefaultEncoder;

use App\Exception\ApiException;
use App\Entity\User;
use App\Exception\Security\UserStatusNotEnabledException;
use App\Exception\Security\UserStatusPendingException;
use App\Exception\Security\UserStatusDisabledException;

class JwtHandler
{
    protected $encoder;

    public function __construct(DefaultEncoder $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param \App\Entity\User $user
     *
     * @return \Tavro\Entity\User
     */
    public function statusCheck(User $user)
    {

        $status = (int)$user->getStatus();

        //defacto assumption, first to avoid unnecessary checks
        if (true === ($status == User::STATUS_ENABLED)) {
            return $user;
        }

        if (true === ($status == (int)User::STATUS_DISABLED)) {
            throw new UserStatusDisabledException(sprintf('Your account (%s) is currently `disabled` and will not be authorized.',
                $user->__toString()));
        }

        if (true === ($status == (int)User::STATUS_PENDING)) {
            throw new UserStatusPendingException(sprintf('Your account (%s) is currently `pending` and cannot be authorized until activated.',
                $user->__toString()));
        }

        if (true === ($status == (int)User::STATUS_OTHER)) {
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