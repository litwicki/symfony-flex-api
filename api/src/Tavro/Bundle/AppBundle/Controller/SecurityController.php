<?php

namespace Tavro\Bundle\AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Form\UserType;
use Tavro\Bundle\CoreBundle\Form\UserRegisterType;
use Symfony\Component\Security\Core\Security;

use Litwicki\Common\Common as Litwicki;

class SecurityController extends Controller
{
    /**
     * Auto authenticate the User.
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     */
    public function authenticate(User $user, $password)
    {
        $token = new UsernamePasswordToken($user, $password, 'main', $user->getRoles());
        $this->get('security.token_storage')->setToken($token);
    }

    /**
     * Authenticate and login a User
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        /**
         * If the User is already logged in, send them to the index page
         */
        $user = $this->getUser();
        if($user instanceof User) {
            return $this->redirect($this->generateUrl('index'));
        }

        $helper = $this->get('security.authentication_utils');

        return $this->render('TavroAppBundle:Security:login.html.twig', array(
            'last_username' => $helper->getLastUsername(),
            'error'         => $helper->getLastAuthenticationError(),
        ));

    }

    /**
     * Create the self User registration form.
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createRegistrationForm()
    {
        /**
         * Do not bind/map this to a User entity because we have to process
         * passwords and api keys with specific encoding logic etc.
         */
        $form = $this->createForm(new UserRegisterType(), array(
            'action' => $this->generateUrl('register'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * The registration page.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function signupAction(Request $request)
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('index');
        }

        $form   = $this->createRegistrationForm();

        return $this->render('TavroAppBundle:Security:signup.html.twig', array(
            'form'   => $form->createView(),
        ));
    }

    /**
     * Process the registration of a new User and automatically authenticate them
     * if the registration is successful.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function registerAction(Request $request)
    {
        $form = $this->createRegistrationForm();
        $form->handleRequest($request);
        $user = null;

        if($form->isValid()) {

            try {

                $data = $form->getViewData();
                $handler = $this->container->get('tavro.handler.users');
                $user = $handler->create(array(
                    'username' => $data['username'],
                    'password' => $data['password'],
                    'email' => $data['email']
                ));

            }
            catch(\Exception $e) {
                $errors[] = $e->getMessage();
            }

        }
        else {
            $errors[] = (string) $form->getErrors(true, false);
        }

        if(empty($errors) && $user instanceof User) {

            /**
             * Authenticate the User, and redirect to 'index'
             */
            $this->authenticate($user, $data['password']);
            $this->addFlash('success', sprintf('Welcome to Tavro, %s!', $user->getUsername()));
            return $this->redirectToRoute('index');

        }
        else {

            /**
             * Refresh the page and display error(s)
             */
            $message = implode('<br>', $errors);
            $this->addFlash('danger', $message);
            return $this->redirectToRoute('signup');

        }

    }

    /**
     * @return \Symfony\Component\Form\Form
     */
    public function createForgotPasswordForm()
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('forgot_password_submit'))
            ->add('email', 'email', array(
                'required' => true
            ))
            ->add('submit', 'submit', array(
                'label' => 'Submit',
                'attr' => array('class' => 'btn btn-default'),
            ));

        return $form->getForm();
    }

    /**
     * @param $token
     *
     * @return \Symfony\Component\Form\Form
     */
    public function createResetPasswordForm($token)
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('reset_password_submit', array('token' => $token)))
            ->add('password', 'password', array(
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Enter your new password'
                )
            ))
            ->add('password_confirm', 'password', array(
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Confirm the password'
                )
            ))
            ->add('submit', 'submit', array(
                'label' => 'Submit',
                'attr' => array('class' => 'btn btn-default'),
            ));

        return $form->getForm();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function forgotAction(Request $request)
    {
        $form = $this->createForgotPasswordForm();
        $page = array(
            'form' => $form->createView()
        );
        return $this->render('TavroAppBundle:Security:forgot.html.twig', $page);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function forgotSubmitAction(Request $request)
    {
        $form = $this->createForgotPasswordForm();
        $form->handleRequest($request);
        $errors = array();

        if($form->isValid()) {

            try {

                $data = $form->getViewData();
                $email = $data['email'];

                $errors = $this->container->get('tavro.validator')->emails($email);

                if(empty($errors)) {

                    $user = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('TavroCoreBundle:User')
                        ->findOneBy(array('email' => $email));

                    if($user instanceof User) {
                        $this->container->get('tavro.handler.users')->sendUserPasswordReset($user);
                    }
                    else {
                        $errors[] = sprintf('No user with email %s was found', $email);
                    }

                }

            }
            catch(\Exception $e) {
                $errors[] = $e->getMessage();
            }

        }
        else {
            $errors[] = (string) $form->getErrors(true, false);
        }

        if(!empty($errors)) {
            $this->addFlash('danger', implode('<br>', $errors));
            return $this->redirectToRoute('forgot_password');
        }
        else {
            $this->addFlash('success', 'A password reset link has been sent via email');
            return $this->redirectToRoute('index');
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $token
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function resetAction(Request $request, $token)
    {

        $this->get('security.token_storage')->setToken(null);
        $this->get('request')->getSession()->invalidate();

        $user = $this->getDoctrine()
                ->getManager()
                ->getRepository('TavroCoreBundle:User')
                ->findOneBy(array('password_token' => $token));

        if(!$user instanceof User) {
            throw new NotFoundHttpException('No User found for this token!');
        }

        if($user->getPasswordTokenExpire() < new \DateTime()) {
            throw new \Exception('This password reset token has expired!');
        }

        $form = $this->createResetPasswordForm($token);

        $page = array(
            'form' => $form->createView()
        );

        return $this->render('TavroAppBundle:Security:reset.html.twig', $page);

    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $token
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function resetSubmitAction(Request $request, $token)
    {
        $form = $this->createResetPasswordForm($token);
        $form->handleRequest($request);
        $errors = array();

        if($form->isValid()) {

            try {

                $data = $form->getViewData();
                $password = $data['password'];
                $passwordConfirm = $data['password_confirm'];

                if($password !== $passwordConfirm) {
                    $errors[] = 'Passwords do not match!';
                }

                $this->container->get('tavro.validator')->passwordComplexity($password);

                if(empty($errors)) {

                    $user = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('TavroCoreBundle:User')
                        ->findOneBy(array('password_token' => $token));

                    if($user instanceof User) {

                        $user = $this->container->get('tavro.handler.users')->resetPassword($user, $password);
                        $this->authenticate($user, $password);

                    }

                }

            }
            catch(\Exception $e) {
                $errors[] = $e->getMessage();
            }

        }
        else {
            $errors[] = (string) $form->getErrors(true, false);
        }

        if(!empty($errors)) {
            $message = implode('<br>', $errors);
            $this->addFlash('danger', $message);
            $form = $this->createResetPasswordForm($token);
            $page = array(
                'form' => $form->createView()
            );
            return $this->render('TavroAppBundle:Security:reset.html.twig', $page);
        }
        else {
            $this->addFlash('success', 'Your password has been reset!');
            return $this->redirectToRoute('index');
        }
    }

}
