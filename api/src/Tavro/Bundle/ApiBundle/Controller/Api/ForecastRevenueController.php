<?php

namespace Tavro\Bundle\ApiBundle\Controller\Api;

use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiNotFoundException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiRequestLimitException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFieldException;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Tavro\Bundle\CoreBundle\Entity\Forecast;
use Tavro\Bundle\CoreBundle\Entity\Tag;
use Tavro\Bundle\CoreBundle\Entity\ForecastTag;
use Tavro\Bundle\CoreBundle\Entity\ForecastComment;
use Symfony\Component\HttpFoundation\Cookie;

use Litwicki\Common\Common;
use Tavro\Bundle\ApiBundle\Controller\Api\ApiController as ApiController;

class ForecastRevenueController extends ApiController
{

    /**
     * Display all Revenues for this Forecast.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Forecast $forecast
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function byForecast(Request $request, Forecast $forecast, $_format) {
        try {

            $entities = $forecast->getForecastRevenues();

            $items = array();

            foreach ($entities as $entity) {
                $items[] = $entity->getRevenue();
            }

            return $this->apiResponse($entities, [
                'format' => $_format,
                'group'  => 'simple'
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

}