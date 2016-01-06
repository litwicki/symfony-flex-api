<?php

namespace Tavro\Bundle\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Form\UserType;

use Tavro\Bundle\ApiBundle\Exception\ApiException;
use Tavro\Bundle\ApiBundle\Exception\ApiNotFoundException;
use Tavro\Bundle\ApiBundle\Exception\ApiRequestLimitException;
use Tavro\Bundle\ApiBundle\Exception\ApiAccessDeniedException;

use Doctrine\Common\Inflector\Inflector;

use Tavro\Bundle\CoreBundle\Form\UserAvatarType;

use Litwicki\Common\Common;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Tavro\Bundle\ApiBundle\Controller\ApiController;
use Tavro\Bundle\CoreBundle\Entity\Image;

class UserController extends ApiController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userAvatarSaveAction(Request $request, User $user)
    {
        $errors = array();

        try {

            $form = $form = $this->createAvatarForm($user);
            $form->handleRequest($request);

            if($form->isValid()) {

                $handler = $this->container->get('tavro.handler.images');
                $em = $this->getDoctrine()->getManager();

                $data = $form->getViewData();
                $uploadImage = $data['avatar'];
                $image = $handler->upload($uploadImage, $directory = 'avatars');

                $oldAvatar = $user->getAvatar();

                $user->setAvatar($image);
                $em->persist($user);
                $em->flush();

                if($oldAvatar instanceof Image) {
                    $handler->delete($oldAvatar);
                }

            }
            else {
                $errors[] = (string) $form->getErrors(true, false);
                //$errors[] = $form->getErrorsAsString();
            }

        }
        catch(\Exception $e) {
            $errors[] = $e->getMessage();
        }

        if(empty($errors)) {
            $data = $user;
        }
        else {
            $message = implode(',', $errors);
            throw new ApiException($message);
        }

        $data = $this->serialize($data, 'json');
        return $this->apiResponse($data, 'json');

    }

    public function createAvatarForm(User $user)
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('user_avatar_save', array('user' => $user->getId())))
            ->add('avatar', 'file')
            ->add('submit', 'submit', array(
                'label' => 'Save Avatar',
                'attr' => array('class' => 'btn btn-primary btn-sm'),
            ));

        return $form->getForm();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profileAction(Request $request, User $user = null)
    {
        $user = is_null($user) ? $this->getUser() : $user;
        $form = $this->createAvatarForm($user);
        $page = array(
            'avatar_form' => $form->createView(),
            'user' => $user,
        );

        return $this->render('TavroAppBundle:User:profile.html.twig', $page);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userAction(Request $request, User $user)
    {
        $page = array(
            'user' => $user
        );
        return $this->render('TavroAppBundle:User:user.html.twig', $page);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $username
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function usernameAction(Request $request, $username)
    {
        $handler = $this->container->get('tavro.handler.users');
        $user = $handler->repository->findOneBy(array('username' => $username));
        $options = array(
            'request' => $request,
            'user' => $user
        );
        return $this->forward('TavroAppBundle:User:user', $options);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userApiAction(Request $request)
    {
        $user = $this->getUser();
        $page = array('user' => $user);
        return $this->render('TavroAppBundle:User:api.html.twig', $page);
    }

    /**
     * Allow a User to request developer access, which grants them the ability to add new UI Mods
     * to the website. We have set this up as an intermediate process deliberately to throttle "bad"
     * uploads and silliness that is not Tavro related.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function userRequestDevAction(Request $request)
    {
        if(false === $this->isGranted('ROLE_USER')) {
            throw new AccessDeniedException('You must be logged in.');
        }

        if($this->isGranted('ROLE_DEVELOPER')) {
            //return $this->redirectToRoute('index');
        }

        return $this->render('TavroAppBundle:User:request-dev.html.twig');
    }
}