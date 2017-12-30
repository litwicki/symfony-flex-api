<?php

namespace Tavro\Security\Authentication\Provider;

use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @author Jake Litwicki <jake.litwicki@gmail.com>
 * @credit: jwt-auth-bundle by Auth0
 */
interface JWTUserProviderInterface extends UserProviderInterface
{
    /**
     * Loads the user for the given decoded JWT.
     *
     * This method must throw JWTInfoNotFoundException if the user is not
     * found.
     *
     * @param string $jwt The decoded Json Web Token
     *
     * @return UserInterface
     *
     * @throws AuthenticationException if the user is not found
     */
    public function loadUserByJWT($jwt);

    /**
     * Returns an anonymous user
     *
     * This can return a JWTInfoNotFoundException exception if you don't want
     * to handle anonimous users
     *
     * Is recommended to return a user with the role IS_AUTHENTICATED_ANONYMOUSLY
     *
     * @return UserInterface
     *
     * @throws AuthenticationException
     */
    public function getAnonymousUser();

}
