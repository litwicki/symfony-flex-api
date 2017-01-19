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
     * @throws \Exception
     */
    public function logLoginAttempt(Request $request)
    {
        try {

            $now = new \DateTime();
            $now->setTimezone(new \DateTimeZone($this->container->getParameter('timezone')));

            $conn = $this->getDoctrine()->getEntityManager()->getConnection();
            $conn->insert('login_attempts', [
                'ip_addr' => $request->getClientIp(),
                'user_agent' => $request->headers->get('User-Agent'),
                'login_timestamp' => $now->format('Y-m-d H:i:s')
            ]);

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Exception
     */
    public function deleteLoginAttempts(Request $request)
    {
        try {
            $sql = "DELETE FROM login_attempts WHERE ip_addr = :ip_addr";

            $params = array('ip_addr' => $request->getClientIp());
            $stmt   = $this->getDoctrine()->getEntityManager()->getConnection()->prepare($sql);
            $stmt->execute($params);
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function tokenAuthenticateAction(Request $request)
    {
        $username = $request->request->get('username');
        $password = $request->request->get('password');

        /**
         * If after three attempts you've still failed, you'll have to wait
         * 15 minutes to attempt to login again..
         */
        $conn = $this->getDoctrine()->getEntityManager()->getConnection();
        $sql = 'SELECT * FROM login_attempts WHERE ip_addr = :ip_addr';
        $params = array('ip_addr' => $request->getClientIp());
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $attempts = $stmt->fetchAll();

        $now = new \DateTime();
        $now->setTimezone(new \DateTimeZone($this->container->getParameter('timezone')));

        foreach($attempts as $attempt) {
            $timestamp = $attempt['login_timestamp'];
            $loginAttempt = new \DateTime($timestamp);
            $interval = $now->diff($loginAttempt);
            $minutes = $interval->format('%i');
        }

        if(count($attempts) >= 3 && $minutes <= 15) {
            throw new JWTMaximumLoginAttemptsException('You have reached the maximum number of login attempts. Please try again in 15 minutes.');
        }
        elseif(count($attempts) > 3 && $minutes > 15) {
            $this->deleteLoginAttempts($request);
        }

        $user = $this->getDoctrine()->getRepository('TavroCoreBundle:User')->findOneBy(['username' => $username]);

        if(!$user) {
            throw $this->createNotFoundException();
        }

        // password check
        if(!$this->get('security.password_encoder')->isPasswordValid($user, $password)) {
            $this->logLoginAttempt($request);
            throw $this->createAccessDeniedException();
        }

        // Use LexikJWTAuthenticationBundle to create JWT token that hold only information about user name
        $token = $this->get('lexik_jwt_authentication.encoder')->encode(['username' => $user->getUsername()]);

        if(count($attempts)) {
            $this->deleteLoginAttempts($request);
        }

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