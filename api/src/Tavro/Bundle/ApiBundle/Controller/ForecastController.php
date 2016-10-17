<?php

namespace Tavro\Bundle\ApiBundle\Controller;

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
use Tavro\Bundle\ApiBundle\Controller\DefaultController as ApiController;

class ForecastController extends ApiController
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
    public function staffPersonsAction(Request $request, Forecast $forecast, $_format)
    {
        try {

            $entities = $forecast->getForecastStaffPersons();

            $items = array();

            foreach($entities as $entity) {
                $items[] = $entity->getStaffPerson();
            }

            return $this->apiResponse($entities, [
                'format' => $_format,
                'group' => 'simple'
            ]);

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Display all Expenses for this Forecast.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Forecast $forecast
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function expensesAction(Request $request, Forecast $forecast, $_format)
    {
        try {

            $entities = $forecast->getForecastExpenses();

            $items = array();

            foreach($entities as $entity) {
                $items[] = $entity->getExpense();
            }

            return $this->apiResponse($entities, [
                'format' => $_format,
                'group' => 'simple'
            ]);

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

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
    public function revenuesAction(Request $request, Forecast $forecast, $_format)
    {
        try {

            $entities = $forecast->getForecastRevenues();

            $items = array();

            foreach($entities as $entity) {
                $items[] = $entity->getRevenue();
            }

            return $this->apiResponse($entities, [
                'format' => $_format,
                'group' => 'simple'
            ]);

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Display all Comments for this Forecast.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Forecast $forecast
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function commentsAction(Request $request, Forecast $forecast, $_format)
    {
        try {

            $entities = $forecast->getForecastComments();

            $items = array();

            foreach($entities as $entity) {
                $items[] = $entity->getComment();
            }

            return $this->apiResponse($entities, [
                'format' => $_format,
                'group' => 'simple'
            ]);

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Forecast $forecast
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function newCommentAction(Request $request, Forecast $forecast, $_format)
    {
        try {

            $data = json_decode($request->getContent(), TRUE);

            $handler = $this->getHandler('comments');
            $comment = $handler->post($request, $data);

            /**
             * Attach the Comment to the Forecast
             */
            $this->getHandler('forecast_comments')->post($request, array(
                'comment' => $comment->getId(),
                'forecast' => $forecast->getId()
            ));

            $routeOptions = array(
                'entity'  => 'comments',
                'id'      => $comment->getId(),
                'format'  => $_format,
            );

            return $this->forward('TavroApiBundle:Default:get', $routeOptions);

        }
        catch(\Exception $e) {
            throw $e;
        }

    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Forecast $forecast
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function newRevenueAction(Request $request, Forecast $forecast, $_format)
    {
        try {

            $data = json_decode($request->getContent(), TRUE);

            if(!$forecast->getId() != $data['forecast_id']) {
                throw new InvalidFieldException('Invalid or mismatching Forecast Id');
            }

            $handler = $this->getHandler('forecast_revenues');
            $entity = $handler->post($request, $data);

            $routeOptions = array(
                'entity'  => 'forecast_revenues',
                'id'      => $entity->getId(),
                'format'  => $_format,
            );

            return $this->forward('TavroApiBundle:Default:get', $routeOptions);

        }
        catch(\Exception $e) {
            throw $e;
        }

    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Forecast $forecast
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function newExpenseAction(Request $request, Forecast $forecast, $_format)
    {
        try {

            $data = json_decode($request->getContent(), TRUE);

            if(!$forecast->getId() != $data['forecast_id']) {
                throw new InvalidFieldException('Invalid or mismatching Forecast Id');
            }

            $handler = $this->getHandler('forecast_expenses');
            $entity = $handler->post($request, $data);

            $routeOptions = array(
                'entity'  => 'forecast_expenses',
                'id'      => $entity->getId(),
                'format'  => $_format,
            );

            return $this->forward('TavroApiBundle:Default:get', $routeOptions);

        }
        catch(\Exception $e) {
            throw $e;
        }

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
        try {

            $data = json_decode($request->getContent(), TRUE);

            if(!$forecast->getId() != $data['forecast_id']) {
                throw new InvalidFieldException('Invalid or mismatching Forecast Id');
            }

            $handler = $this->getHandler('forecast_staff_persons');
            $entity = $handler->post($request, $data);

            $routeOptions = array(
                'entity'  => 'forecast_staff_persons',
                'id'      => $entity->getId(),
                'format'  => $_format,
            );

            return $this->forward('TavroApiBundle:Default:get', $routeOptions);

        }
        catch(\Exception $e) {
            throw $e;
        }

    }

}