<?php namespace Tavro\Bundle\ApiBundle\Security\Jwt;

use Tavro\Bundle\ApiBundle\Exception\ApiException;
use Tavro\Bundle\CoreBundle\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\DefaultEncoder;

class JwtHandler
{
    protected $encoder;

    public function __construct(DefaultEncoder $encoder)
    {
        $this->encoder = $encoder;
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