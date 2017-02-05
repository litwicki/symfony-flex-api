<?php

namespace Tavro\Bundle\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
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
use Tavro\Bundle\ApiBundle\Controller\DefaultController as ApiController;

class AuthController extends ApiController
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

        if($loginAttemptHandler->lock($request)) {
            throw new JWTMaximumLoginAttemptsException('You have reached the maximum number of login attempts. Please try again in 15 minutes.');
        }
        elseif($loginAttemptHandler->unlock($request)) {
            $loginAttemptHandler->clear($request);
        }

        $user = $this->getDoctrine()->getRepository('TavroCoreBundle:User')->findOneBy(['username' => $username]);

        if(!$user) {
            throw $this->createNotFoundException('The Username or Password were invalid.');
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

    /**
     * By design, only allow the current user to do this for his/her self.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function resetAction(Request $request)
    {
        try {

            $data = json_decode($request->getContent(), TRUE);

            $person = $this->getDoctrine()->getRepository('TavroCoreBundle:Person')->findOneBy([
               'email' => $data['email']
            ]);

            if(!$person instanceof Person) {
                throw new \Exception('We are unable to process your forgot password request.');
            }

            $user = $person->getUser();

            if(!$user instanceof User) {
                throw new \Exception('We are unable to process your forgot password request.');
            }

            $handler = $this->getHandler('users');
            $handler->resetPassword($request, $user, $data);

            return $this->apiResponse($user, [
                'message' => sprintf('An email has been sent to %s', $data['email']),
            ]);

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * When a User has forgotten their password, set a reset token and email forcing
     * them to verify themselves before allowing the actual reset to complete.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function sendResetTokenAction(Request $request)
    {
        try {

            $data = json_decode($request->getContent(), TRUE);

            $person = $this->getDoctrine()->getRepository('TavroCoreBundle:Person')->findOneBy([
               'email' => $data['email']
            ]);

            if(!$person instanceof Person) {
                throw new \Exception('We are unable to process your forgot password request.');
            }

            $user = $person->getUser();

            if(!$user instanceof User) {
                throw new \Exception('We are unable to process your forgot password request.');
            }

            $handler = $this->getHandler('users');
            $handler->forgotPassword($request, $user);

            return $this->apiResponse($user, [
                'message' => sprintf('An email has been sent to %s', $data['email']),
            ]);

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

}