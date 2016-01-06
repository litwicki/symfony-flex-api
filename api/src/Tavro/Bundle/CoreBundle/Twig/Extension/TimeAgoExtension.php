<?php

namespace Tavro\Bundle\CoreBundle\Twig\Extension;

use Litwicki\Common\Common;

class TimeAgoExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('timeAgo', array($this, 'timeAgoFilter')),
        );
    }

    public function timeAgoFilter($date)
    {
        if(is_null($date)) {
            return 'NA';
        }

        return Common::timeAgo($date);
    }

    public function getName()
    {
        return 'time_ago_extension';
    }
}