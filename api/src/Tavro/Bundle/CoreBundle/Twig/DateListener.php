<?php

namespace Tavro\Bundle\CoreBundle\Twig;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class DateListener
{
    protected $twig;

    function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     *
     * @throws \Twig_Error_Runtime
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->twig->getExtension('core')->setTimezone('America/New_York');
    }
}