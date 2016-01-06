<?php

namespace Tavro\Bundle\ApiBundle\Security;

use Tavro\Bundle\CoreBundle\Security\UserProvider;

use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $providerKey
     *
     * @return \Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken
     */
    public function createToken(Request $request, $providerKey)
    {
        // look for a tavro-api-key header
        $apiKey = $request->server->get('PHP_AUTH_USER');
        $apiPassword = $request->server->get('PHP_AUTH_PASSWORD');

        /**
         * @TODO: something with the api-password with standard http_basic authentication..
         */

        if (!$apiKey) {
            //throw new BadCredentialsException('Invalid or missing Api credentials!');
            return null;
        }

        return new PreAuthenticatedToken(
            'anon.',
            $apiKey,
            $providerKey
        );
    }

    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @param \Tavro\Bundle\CoreBundle\Security\UserProvider $userProvider
     * @param $providerKey
     *
     * @return \Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken
     * @throws \Exception
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {

        try {

            if (!$userProvider instanceof UserProviderInterface) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'The user provider must be an instance of Symfony\Component\Security\Core\User\UserProviderInterface (%s was given).',
                        get_class($userProvider)
                    )
                );
            }

            $apiKey = $token->getCredentials();

            $username = $userProvider->getUsernameForApiKey($apiKey);

            if (!$username) {
                throw new AuthenticationException(
                    sprintf('API Key "%s" does not exist.', $apiKey)
                );
            }

            $user = $userProvider->loadUserByUsername($username);

            return new PreAuthenticatedToken(
                $user,
                $apiKey,
                $providerKey,
                $user->getRoles()
            );

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
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        throw $exception;
    }

}