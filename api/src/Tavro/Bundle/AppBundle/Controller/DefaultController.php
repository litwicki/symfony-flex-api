<?php

namespace Tavro\Bundle\AppBundle\Controller;

use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DefaultController extends Controller
{
    /**
     *  Fix all routes with a trailing slash to not include said
     *  trailing slash, because /{account}/ and /{account} are otherwise
     *  completely unique routes in the application
     */
    public function removeTrailingSlashAction(Request $request)
    {
        $pathInfo = $request->getPathInfo();
        $requestUri = $request->getRequestUri();

        $url = str_replace($pathInfo, rtrim($pathInfo, ' /'), $requestUri);

        return $this->redirect($url, 301);

    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $page = array(
            'user' => $this->getUser()
        );
        return $this->render('TavroAppBundle:Default:index.html.twig', $page);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function termsAction(Request $request)
    {
        return $this->render('TavroAppBundle:Default:terms.html.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function privacyAction(Request $request)
    {
        return $this->render('TavroAppBundle:Default:privacy.html.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function aboutAction(Request $request)
    {
        return $this->render('TavroAppBundle:Default:about.html.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function premiumAction(Request $request)
    {
        return $this->render('TavroAppBundle:Default:premium.html.twig');
    }

}
