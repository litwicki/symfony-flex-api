<?php

namespace App\Security\Authentication\Authenticator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\SimpleFormAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use App\Security\Authentication\Provider\UserProvider;

class UserAuthenticator implements SimpleFormAuthenticatorInterface
{
    private $provider;
    private $encoder;

    /**
     * UserAuthenticator constructor.
     *
     * @param $encoder
     * @param \App\Security\Authentication\Provider\UserProvider $provider
     */
    public function __construct($encoder, UserProvider $provider)
    {
        $this->encoder = $encoder;
        $this->provider = $provider;
    }

    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @param \Symfony\Component\Security\Core\User\UserProviderInterface $userProvider
     * @param $providerKey
     *
     * @return \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken
     * @throws \Exception
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        try {

            $user = $this->provider->loadUserByUsername($token->getUsername());
            
            if($user->getStatus() == 0) {
                throw new AuthenticationException(sprintf('User "%s" is currently disabled!', $token->getUsername()));
            }

            if ($this->encoder->isPasswordValid($user->getPassword(), $token->getCredentials(), $user->getSalt())) {
                return new UsernamePasswordToken(
                    $user,
                    $user->getPassword(),
                    $providerKey,
                    $user->getRoles()
                );
            }
            else {
                //@TODO: do we want to make this explicit or be vague on purpose?
                //Possibly return "Invalid username or password" so users don't know they guessed a proper username?
                throw new AuthenticationException(sprintf('Invalid password entered for username "%s"', $token->getUsername()));
            }

        }
        catch (UsernameNotFoundException $e) {
            throw new AuthenticationException('Invalid Username or Password');
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @param $providerKey
     *
     * @return bool
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof UsernamePasswordToken && $token->getProviderKey() === $providerKey;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $username
     * @param $password
     * @param $providerKey
     *
     * @return \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken
     */
    public function createToken(Request $request, $username, $password, $providerKey)
    {
        return new UsernamePasswordToken($username, $password, $providerKey);
    }

}
