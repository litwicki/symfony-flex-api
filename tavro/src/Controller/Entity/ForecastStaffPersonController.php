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
use App\Entity\ForecastComment;
use Symfony\Component\HttpFoundation\Cookie;

use Litwicki\Common\Common;
use App\Controller\Api\EntityApiController;

class ForecastStaffPersonController extends EntityApiController
{

    /**
     * Display all Staff for this Forecast.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Forecast $forecast
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
     * @param \App\Entity\Forecast $forecast
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