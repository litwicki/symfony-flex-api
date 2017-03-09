<?php

namespace Tavro\Bundle\ApiBundle\Controller\Entity;

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
use Tavro\Bundle\CoreBundle\Entity\ForecastComment;
use Symfony\Component\HttpFoundation\Cookie;

use Litwicki\Common\Common;
use Tavro\Bundle\ApiBundle\Controller\Api\EntityApiController;

class ForecastStaffPersonController extends EntityApiController
{

    /**
     * Display all Staff for this Forecast.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Forecast $forecast
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
     * @param \Tavro\Bundle\CoreBundle\Entity\Forecast $forecast
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function newStaffPersonAction(Request $request, Forecast $forecast, $_format)
    {
        $data = null;

        try {

            $data = json_decode($request->getContent(), TRUE);

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