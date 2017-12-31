<?php

namespace App\Controller\Entity;

use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Exception\Api\ApiException;
use App\Exception\Api\ApiNotFoundException;
use App\Exception\Api\ApiRequestLimitException;
use App\Exception\Api\ApiAccessDeniedException;
use App\Exception\Form\InvalidFieldException;
use App\Exception\Form\InvalidFormException;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use App\Entity\Forecast;
use App\Entity\Tag;
use App\Entity\ForecastTag;
use App\Entity\ForecastComment;
use Symfony\Component\HttpFoundation\Cookie;

use Litwicki\Common\Common;
use App\Controller\Api\EntityApiController;

class ForecastRevenueController extends EntityApiController
{

    /**
     * Display all Revenues for this Forecast.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Forecast $forecast
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function byForecast(Request $request, Forecast $forecast, $_format)
    {
        $data = null;

        try {

            $entities = $forecast->getForecastRevenues();

            $data = array();

            foreach ($entities as $entity) {
                $data[] = $entity->getRevenue();
            }

            $options = [
                'format' => $_format,
                'group'  => 'simple'
            ];
        }
        catch(\Exception $e) {
            $options = $this->getExceptionOptions($e, $_format);
        }

        return $this->apiResponse($data, $options);
    }

}