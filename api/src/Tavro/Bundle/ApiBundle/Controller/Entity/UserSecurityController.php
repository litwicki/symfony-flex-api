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
        $data = null;

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

            $data = $user;

            $options = [
                'message' => sprintf('An email has been sent to `%s` to reset your password.', $data['email']),
            ];

        }
        catch(\Exception $e) {
            $options = [
                'format' => $_format,
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }

        return $this->apiResponse($data, $options);
    }

}