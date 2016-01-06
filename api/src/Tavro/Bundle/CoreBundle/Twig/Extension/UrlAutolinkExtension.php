<?php

namespace Tavro\Bundle\CoreBundle\Twig\Extension;

use Litwicki\Common\Common;

class UrlAutolinkExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('urlAutolink', array($this, 'urlAutolinkFilter')),
        );
    }

    public function urlAutolinkFilter($string)
    {
        return Common::urlAutolink($string);
    }

    public function getName()
    {
        return 'url_autolink_extension';
    }
}