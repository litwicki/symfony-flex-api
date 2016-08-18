<?php

namespace Tavro\Bundle\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiNotFoundException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiRequestLimitException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;

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
     */
    public function tokenAuthenticateAction(Request $request)
    {
        $username = $request->request->get('username');
        $password = $request->request->get('password');

        $user = $this->getDoctrine()->getRepository('TavroCoreBundle:User')->findOneBy(['username' => $username]);

        if(!$user) {
            throw $this->createNotFoundException();
        }

        // password check
        if(!$this->get('security.password_encoder')->isPasswordValid($user, $password)) {
            throw $this->createAccessDeniedException();
        }

        // Use LexikJWTAuthenticationBundle to create JWT token that hold only information about user name
        $token = $this->get('lexik_jwt_authentication.encoder')->encode(['username' => $user->getUsername()]);

        // Return genereted tocken
        return new JsonResponse(['token' => $token]);
    }

    /**
     * By design, only allow the current user to do this for his/her self.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $format
     *
     * @throws \Exception
     */
    public function resetAction(Request $request, $format)
    {
        try {
            $handler = $this->container->get('tavro.handler.users');
            $user = $this->getUser();
            $data = json_decode($request->getContent(), true);
            $handler->resetPassword($user, $data);
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
     * @param $format
     *
     * @throws \Exception
     */
    public function forgotAction(Request $request, $format)
    {
        try {

            //set the password tokens etc.
            $handler = $this->container->get('tavro.handler.users');
            $user = $this->getUser();
            $handler->forgotPassword($user);

            //email the user the link to reset their password
            $this->getContainer()->get('mailer')->sendPasswordReset($user);

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

}