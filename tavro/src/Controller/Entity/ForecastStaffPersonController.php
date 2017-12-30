<?php

namespace Tavro\Controller\Entity;

use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Tavro\Exception\Api\ApiException;
use Tavro\Exception\Api\ApiNotFoundException;
use Tavro\Exception\Api\ApiRequestLimitException;
use Tavro\Exception\Api\ApiAccessDeniedException;
use Tavro\Exception\Form\InvalidFieldException;
use Tavro\Exception\Form\InvalidFormException;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Tavro\Entity\Forecast;
use Tavro\Entity\Tag;
use Tavro\Entity\ForecastComment;
use Symfony\Component\HttpFoundation\Cookie;

use Litwicki\Common\Common;
use Tavro\Controller\Api\EntityApiController;

class ForecastStaffPersonController extends EntityApiController
{

    /**
     * Display all Staff for this Forecast.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Entity\Forecast $forecast
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function byForecastAction(Request $request, Forecast $forecast, $_format)
    {
        $data = null;

        try {

            $entities = $forecast->getForecastStaffPersons();

            $data = array();

            foreach ($entities as $entity) {
                $data[] = $entity->getStaffPerson();
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

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Entity\Forecast $forecast
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function newStaffPersonAction(Request $request, Forecast $forecast, $_format)
    {
        $data = null;

        try {

            $data = $this->getPayload($request);

            if(!$forecast->getId() != $data['forecast_id']) {
                throw new InvalidFieldException('Invalid or missing Forecast Id');
            }

            $handler = $this->getHandler('forecast_staff_persons');
            $data = $handler->post($request, $data);

            $options = [
                'code' => Response::HTTP_CREATED,
                'format' => $_format,
            ];

        }
        catch(\Exception $e) {
            $options = $this->getExceptionOptions($e, $_format);
        }

        return $this->apiResponse($data, $options);

    }

}