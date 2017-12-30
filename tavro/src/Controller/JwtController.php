<?php

namespace Tavro\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Tavro\Exception\ApiNotAuthorizedException;
use Tavro\Exception\JWT\JWTMaximumLoginAttemptsException;
use Tavro\Exception\Security\InvalidCredentialsException;
use Tavro\Exception\Security\UsernameNotFoundException;
use Tavro\Exception\Api\ApiException;
use Tavro\Exception\Api\ApiNotFoundException;
use Tavro\Exception\Api\ApiRequestLimitException;
use Tavro\Exception\Api\ApiAccessDeniedException;
use Tavro\Exception\Form\InvalidFormException;

use Tavro\Entity\Person;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Tavro\Entity\User;
use Symfony\Component\HttpFoundation\Cookie;

use Litwicki\Common\Common;
use Tavro\Controller\DefaultController;

class JwtController extends DefaultController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function tokenAuthenticateAction(Request $request)
    {
        $loginAttemptHandler = $this->container->get('tavro.auth.login_attempts');

        $username = $request->request->get('username');
        $password = $request->request->get('password');
        $user = false;
        $invalidMessage = 'Unable to authenticate, invalid username or password.';

        if($loginAttemptHandler->lock($request)) {
            throw new JWTMaximumLoginAttemptsException('You have reached the maximum number of login attempts. Please try again in 15 minutes.');
        }
        elseif($loginAttemptHandler->unlock($request)) {
            $loginAttemptHandler->clear($request);
        }

        $person = $this->getDoctrine()->getRepository('TavroCoreBundle:Person')->findOneBy([
           'email' => $username
        ]);

        $user = ($person instanceof Person) ? $person->getUser() : false;

        /**
         * If we're not able to authenticate the User, deliberately respond with a vague
         * error message so we're not indicating whether or not specifically the `username`
         * or `password` were invalid in their submission. This is by design.
         */

        if(false === ($user instanceof User)) {

            $user = $this->getDoctrine()->getRepository('TavroCoreBundle:User')->findOneBy(['username' => $username]);

            if(false === ($user instanceof User)) {
                $logMessage = sprintf('Username %s could not be found by %s.', $username, $request->getClientIp());
                $this->get('logger')->info($logMessage);
                throw new ApiNotAuthorizedException($invalidMessage);
            }

        }

        /**
         * Check the User we have has a password that matches what was submitted.
         */
        if(!$this->get('security.password_encoder')->isPasswordValid($user, $password)) {
            $loginAttemptHandler->log($request);
            $logMessage = sprintf('Password for `%s` entered incorrectly by %s.', $username, $request->getClientIp());
            $this->get('logger')->info($logMessage);
            throw new ApiNotAuthorizedException($invalidMessage);
        }

        $loginAttemptHandler->clear($request);

        $jwtHandler = $this->get('tavro_api.jwt_token_handler');

        /**
         * If the User is not properly activated, do not even respond with a Token.
         */
        $user = $jwtHandler->statusCheck($user);
        $token = $jwtHandler->createToken($user);

        // Return genereted tocken
        return new JsonResponse(['token' => $token]);
    }

    public function tokenLogoutAction(Request $request)
    {
        /**
         * Destroy the JWT Token
         */
    }

}