<?php

namespace Tavro\Bundle\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Tavro\Bundle\ApiBundle\Exception\ApiNotAuthorizedException;
use Tavro\Bundle\ApiBundle\Exception\Security\UserPasswordTokenMissingException;
use Tavro\Bundle\ApiBundle\Exception\Security\UserPasswordTokenInvalidException;
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
use Tavro\Bundle\ApiBundle\Controller\Api\ApiController;

class SecurityController extends ApiController
{

    /**
     * @param array $data
     *
     * @throws \Exception
     */
    public function validateData(array $data = array())
    {
        if(!isset($data['email'])) {
            throw new \Exception('Email must be provided to send a reset token.');
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Please provide a valid email address.');
        }
    }

    /**
     * By design, only allow the current user to do this for his/her self.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function resetAction(Request $request, $_format)
    {
        try {

            $data = $this->getPayload($request);

            if(!isset($data['password_token'])) {
                throw new UserPasswordTokenMissingException('`password_token` is required to update your password.');
            }

            $this->validateData($data);

            $person = $this->getDoctrine()->getRepository('TavroCoreBundle:Person')->findOneBy([
               'email' => $data['email']
            ]);

            //deliberately vague error response so as not to alert the User whether or not the email is in use
            if(!$person instanceof Person) {
                throw new ApiNotFoundException('We are unable to process your forgot password request.');
            }

            $user = $person->getUser();

            if(false === ($user->getPasswordToken() === $data['password_token'])) {
                throw new UserPasswordTokenInvalidException('Password Token is no longer valid or incorrect.');
            }

            if(!$user instanceof User) {
                throw new ApiNotFoundException('We are unable to process your forgot password request.');
            }

            $handler = $this->getHandler('users');
            $handler->resetPassword($request, $user, $data);

            $data = $user;

            $options = [
                'format' => $_format,
                'message' => sprintf('Password reset for user with email `%s`', $data['email']),
            ];

        }
        catch(\Exception $e) {
            $options = $this->getExceptionOptions($e, $_format);
        }

        return $this->apiResponse($data, $options);
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
    public function sendResetTokenAction(Request $request, $_format)
    {
        $data = null;

        try {

            $data = $this->getPayload($request);

            $this->validateData($data);

            $email = $data['email'];

            $person = $this->getDoctrine()->getRepository('TavroCoreBundle:Person')->findOneBy([
               'email' => $email
            ]);

            if(!$person instanceof Person) {
                throw new \Exception('We are unable to process your forgot password request.');
            }

            $user = $person->getUser();

            if(!$user instanceof User) {
                throw new \Exception('We are unable to process your forgot password request.');
            }

            $handler = $this->getHandler('users');
            $data = $handler->forgotPassword($request, $user);

            $options = [
                'format' => $_format,
                'message' => sprintf('An email has been sent to `%s` to reset your password.', $email),
            ];

        }
        catch(\Exception $e) {
            $options = $this->getExceptionOptions($e, $_format);
        }

        return $this->apiResponse($data, $options);
    }

    /**
     * Activate a User via their GUID.
     *
     *  example: https://tavro.io/activate?guid={GUID}
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function activateAction(Request $request, $_format)
    {
        $data = null;

        try {

            $data = $this->getPayload($request);

            $handler = $this->get('tavro.security_handler');

            $data = $handler->activate($request);

            $options = [
                'format' => $_format,
                'message' => '',
            ];

        }
        catch(\Exception $e) {
            $options = $this->getExceptionOptions($e, $_format);
        }

        return $this->apiResponse($data, $options);
    }

}