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
        return $this->render('TavroAppBundle:Documentation:user.html.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function organizationAction(Request $request)
    {
        return $this->render('TavroAppBundle:Documentation:organization.html.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function expenseAction(Request $request)
    {
        return $this->render('TavroAppBundle:Documentation:expense.html.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fundingAction(Request $request)
    {
        return $this->render('TavroAppBundle:Documentation:funding.html.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function nodeAction(Request $request)
    {
        return $this->render('TavroAppBundle:Documentation:node.html.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function productAction(Request $request)
    {
        return $this->render('TavroAppBundle:Documentation:product.html.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function revenueAction(Request $request)
    {
        return $this->render('TavroAppBundle:Documentation:revenue.html.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function serviceAction(Request $request)
    {
        return $this->render('TavroAppBundle:Documentation:service.html.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function shareholderAction(Request $request)
    {
        return $this->render('TavroAppBundle:Documentation:shareholder.html.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function commentAction(Request $request)
    {
        return $this->render('TavroAppBundle:Documentation:comment.html.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tagAction(Request $request)
    {
        return $this->render('TavroAppBundle:Documentation:tag.html.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function imageAction(Request $request)
    {
        return $this->render('TavroAppBundle:Documentation:image.html.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function customerAction(Request $request)
    {
        return $this->render('TavroAppBundle:Documentation:customer.html.twig');
    }

}
