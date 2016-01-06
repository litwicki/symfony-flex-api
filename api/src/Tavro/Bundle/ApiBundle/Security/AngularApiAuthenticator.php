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

use Tavro\Bundle\ApiBundle\Security\ApiAuthenticator;

class AngularApiAuthenticator extends ApiAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
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
        $apiKey = base64_decode($request->headers->get('tavro-api-key'));

        if (!$apiKey) {
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

            if($apiKey === 'undefined' || is_null($apiKey)) {

                return new PreAuthenticatedToken(
                    'anon',
                    null,
                    $providerKey,
                    array('ROLE_API')
                );

            }
            else {
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

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

}