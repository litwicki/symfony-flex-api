<?php

namespace App\Twig\Extensions;

class ApiConfigExtension extends \Twig_Extension
{
    public $container;

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('parameter', function($name)
            {
                return $this->container->getParameter($name);
            })
        ];
    }


    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'api';
    }
}