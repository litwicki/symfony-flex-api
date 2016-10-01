<?php

namespace Tavro\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TavroAdminBundle:Default:index.html.twig');
    }
}
