<?php

namespace Tavro\Bundle\CoreBundle\Twig\Extension;

use Litwicki\Common\Common;

class ImageCleanupExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('imgCleanup', array($this, 'imgCleanupFilter')),
        );
    }

    public function imgCleanupFilter($string)
    {
        return Common::imgCleanup($string);
    }

    public function getName()
    {
        return 'img_cleanup_extension';
    }
}