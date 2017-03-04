<?php

namespace Tavro\Bundle\ApiBundle\Controller\Entity;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiNotFoundException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiRequestLimitException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;
use Symfony\Component\EventDispatcher\EventDispatcher;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Tavro\Bundle\CoreBundle\Entity\Person;
use Tavro\Bundle\CoreBundle\Entity\User;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;

use Litwicki\Common\Common;
use Tavro\Bundle\ApiBundle\Controller\Api\ApiController as ApiController;

class UserSecurityController extends ApiController
{
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
                'message' => sprintf('Password reset for user with email `%s`', $data['email']),
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
                'message' => sprintf('An email has been sent to `%s` to reset your password.', $data['email']),
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
     * @param Request $request
     * @param User $user
     * @param $_format
     * @return Response
     */
    public function resetApiKeyAction(Request $request, User $user, $_format)
    {
        try {
            $handler = $this->container->get('tavro.handler.users');
            $handler->resetApiKey($user);
            $cookie = new Cookie('api_key', $user->getApiKey(), 0, '/', NULL, FALSE, FALSE);
            $data = $this->serialize($user, $_format);
            $response = $this->apiResponse($data, $_format);
            $response->headers->setCookie($cookie);
            $handler->reauthenticate($user);
            return $response;
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param User $user
     * @param $_format
     * @return Response
     */
    public function resetApiPasswordAction(Request $request, User $user, $_format)
    {
        try {
            $handler = $this->container->get('tavro.handler.users');
            $handler->resetApiPassword($user);
            $cookie = new Cookie('api_password', $user->getApiPassword(), 0, '/', NULL, FALSE, FALSE);
            $data = $this->serialize($user, $_format);
            $response = $this->apiResponse($data, $_format);
            $response->headers->setCookie($cookie);
            $handler->reauthenticate($user);
            return $response;
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

}