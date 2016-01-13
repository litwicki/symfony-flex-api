<?php

namespace Tavro\Bundle\AppBundle\Controller;

use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Tavro\Bundle\AppBundle\Controller\DefaultController;

class DocumentationController extends DefaultController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        return $this->render('TavroAppBundle:Documentation:index.html.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userAction(Request $request)
    {
        $page = array(
            'user' => $this->getUser()
        );
        return $this->render('TavroAppBundle:Documentation:user.html.twig', $page);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function organizationAction(Request $request)
    {
        $page = array(
            'user' => $this->getUser()
        );
        return $this->render('TavroAppBundle:Documentation:organization.html.twig', $page);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function expenseAction(Request $request)
    {
        $page = array(
            'user' => $this->getUser()
        );
        return $this->render('TavroAppBundle:Documentation:expense.html.twig', $page);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fundingAction(Request $request)
    {
        $page = array(
            'user' => $this->getUser()
        );
        return $this->render('TavroAppBundle:Documentation:funding.html.twig', $page);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function nodeAction(Request $request)
    {
        $page = array(
            'user' => $this->getUser()
        );
        return $this->render('TavroAppBundle:Documentation:node.html.twig', $page);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function productAction(Request $request)
    {
        $page = array(
            'user' => $this->getUser()
        );
        return $this->render('TavroAppBundle:Documentation:product.html.twig', $page);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function revenueAction(Request $request)
    {
        $page = array(
            'user' => $this->getUser()
        );
        return $this->render('TavroAppBundle:Documentation:revenue.html.twig', $page);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function serviceAction(Request $request)
    {
        $page = array(
            'user' => $this->getUser()
        );
        return $this->render('TavroAppBundle:Documentation:service.html.twig', $page);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function shareholderAction(Request $request)
    {
        $page = array(
            'user' => $this->getUser()
        );
        return $this->render('TavroAppBundle:Documentation:shareholder.html.twig', $page);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function commentAction(Request $request)
    {
        $page = array(
            'user' => $this->getUser()
        );
        return $this->render('TavroAppBundle:Documentation:comment.html.twig', $page);
    }

}
