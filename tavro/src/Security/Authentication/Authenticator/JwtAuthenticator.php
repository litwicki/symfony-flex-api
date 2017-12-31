<?php

namespace App\Security\Authentication\Authenticator;

use Doctrine\ORM\EntityManager;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\DefaultEncoder;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

use App\Security\Jwt\JwtHandler;
use App\Entity\User;

use App\Exception\JWT\JWTExpiredTokenException;
use App\Exception\JWT\JWTInvalidTokenException;
use App\Exception\JWT\JWTUnverfiedTokenException;

abstract class JwtAuthenticator extends AbstractGuardAuthenticator
{
    private $em;
    private $jwtEncoder;
    private $jwtHandler;
    private $timezone;

    public function __construct(EntityManager $em, DefaultEncoder $jwtEncoder, JwtHandler $jwtHandler, $timezone)
    {
        $this->em = $em;
        $this->jwtEncoder = $jwtEncoder;
        $this->jwtHandler = $jwtHandler;
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
        return new JsonResponse('Auth header required', Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Get the User Credentials from the Token.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    public function getCredentials(Request $request)
    {

        if ( ! $request->headers->has('Authorization')) {
            throw new JWTInvalidTokenException('Missing authorization token.');
        }

        $extractor = new AuthorizationHeaderTokenExtractor('Bearer', 'Authorization');

        $token = $extractor->extract($request);

        if ( ! $token) {
            throw new JWTInvalidTokenException('Invalid authorization token.');
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
     * @throws \Exception
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
                throw new JWTInvalidTokenException('Error loading User from token.');
            }

            /**
             * Based on the Status of the User, we may not want to allow
             * them access to Api actions..
             */
            return $this->jwtHandler->statusCheck($user);

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
        return new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_UNAUTHORIZED);
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