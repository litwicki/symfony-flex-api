<?php

namespace Tavro\Bundle\ApiBundle\Security\Authentication\Authenticator;

use Doctrine\ORM\EntityManager;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\DefaultEncoder;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

use Tavro\Bundle\CoreBundle\Entity\User;

use Tavro\Bundle\ApiBundle\Exception\JWT\JWTExpiredTokenException;
use Tavro\Bundle\ApiBundle\Exception\JWT\JWTInvalidTokenException;
use Tavro\Bundle\ApiBundle\Exception\JWT\JWTUnverfiedTokenException;

use Tavro\Bundle\ApiBundle\Exception\Security\UserStatusNotEnabledException;
use Tavro\Bundle\ApiBundle\Exception\Security\UserStatusPendingException;
use Tavro\Bundle\ApiBundle\Exception\Security\UserStatusDisabledException;

class JwtAuthenticator extends AbstractGuardAuthenticator
{
    private $em;
    private $jwtEncoder;
    private $timezone;

    public function __construct(EntityManager $em, DefaultEncoder $jwtEncoder, $timezone)
    {
        $this->em = $em;
        $this->jwtEncoder = $jwtEncoder;
        $this->timezone = $timezone;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException|null $authException
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse('Auth header required', 401);
    }

    /**
     * Get the User Credentials from the Token.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string|void
     */
    public function getCredentials(Request $request)
    {

        if ( ! $request->headers->has('Authorization')) {
            return;
        }

        $extractor = new AuthorizationHeaderTokenExtractor('Bearer', 'Authorization');

        $token = $extractor->extract($request);

        if ( ! $token) {
            return;
        }

        return $token;
    }

    /**
     * Get the User from the Token.
     *
     * @param mixed $credentials
     * @param \Symfony\Component\Security\Core\User\UserProviderInterface $userProvider
     *
     * @return mixed
     * @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {

            $data = $this->jwtEncoder->decode($credentials);

            if(false === (isset($data['username']))) {
                throw new \Exception('Unable to properly decode credentials from JWT Token.');
            }

            $username = $data['username'];

            $user = $this->em->getRepository('TavroCoreBundle:User')->findOneBy([
                'username' => $username
            ]);

            if(false === ($user instanceof User)) {
                return null;
            }

            /**
             * Based on the Status of the User, we may not want to allow
             * them access to Api actions..
             */

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
                    throw new UserStatusDisabledException(sprintf('%s is currently disabled.', $user->__toString()));
                case ($status == User::STATUS_PENDING):
                    throw new UserStatusPendingException(sprintf('%s is currently pending and cannot be authorized.', $user->__toString()));
                case ($status == User::STATUS_OTHER):
                    throw new UserStatusNotEnabledException(sprintf('Cannot authorize %s, invalid status.', $user->__toString()));
            }

            //this should never be hit... ever.
            throw new \LogicException('JWT Authentication failure.');

        }
        catch (JWTDecodeFailureException $e) {

            switch($e->getReason()) {

                case 'invalid_token':
                    throw new JWTInvalidTokenException($e->getMessage());
                case 'unverified_token':
                    throw new JWTUnverfiedTokenException($e->getMessage());
                case 'expired_token':
                    throw new JWTExpiredTokenException($e->getMessage());
                default:
                    throw $e;

            }

        }
    }

    /**
     * @param mixed $credentials
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * On Authentication Failure, return a response, increment, and log the
     * failed attempt.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException $exception
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse(['message' => $exception->getMessage()], 401);
    }

    /**
     * On success... do stuff
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @param string $providerKey
     *
     * @return null|\Symfony\Component\HttpFoundation\Response|void
     * @throws \Exception
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return;
    }

    /**
     * Does this Method support Remember Me?
     *
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }

}