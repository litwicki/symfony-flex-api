<?php

namespace Tavro\Bundle\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Tavro\Bundle\ApiBundle\Exception\ApiNotAuthorizedException;
use Tavro\Bundle\ApiBundle\Exception\JWT\JWTMaximumLoginAttemptsException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiNotFoundException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiRequestLimitException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;

use Tavro\Bundle\CoreBundle\Entity\Person;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Tavro\Bundle\CoreBundle\Entity\User;
use Symfony\Component\HttpFoundation\Cookie;

use Litwicki\Common\Common;
use Tavro\Bundle\ApiBundle\Controller\DefaultController;

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

        if(false === ($user instanceof User)) {

            $user = $this->getDoctrine()->getRepository('TavroCoreBundle:User')->findOneBy(['username' => $username]);

            if(false === ($user instanceof User)) {
                //make this deliberately vague so users don't know if username OR password are invalid
                //for the purposes of making brut force a bit more difficult..
                throw new ApiNotAuthorizedException('Unable to authorize you: username or password invalid.');
            }

        }

        // password check
        if(!$this->get('security.password_encoder')->isPasswordValid($user, $password)) {
            $loginAttemptHandler->log($request);
            throw $this->createAccessDeniedException();
        }

        // Use LexikJWTAuthenticationBundle to create JWT token that hold only information about user name
        $token = $this->get('lexik_jwt_authentication.encoder')->encode(['username' => $user->getUsername()]);

        $loginAttemptHandler->clear($request);

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