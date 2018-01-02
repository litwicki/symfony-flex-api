<?php

namespace App\Controller\AccountEntity;

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
use App\Controller\Api\AccountEntityApiController;

class ForecastController extends AccountEntityApiController
{

    /**
     * Display all Comments for this Forecast.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Forecast $forecast
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function commentsAction(Request $request, Forecast $forecast, $_format)
    {
        $data = null;

        try {

            $entities = $forecast->getForecastComments();

            $data = array();

            foreach($entities as $entity) {
                $data[] = $entity->getComment();
            }

            $options = [
                'format' => $_format,
                'group' => 'simple'
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
    public function newCommentAction(Request $request, Forecast $forecast, $_format)
    {
        $data = null;

        try {

            $data = $this->getPayload($request);

            $handler = $this->getHandler('comments');
            $comment = $handler->post($request, $data);

            /**
             * Attach the Comment to the Forecast
             */
            $this->getHandler('forecast_comments')->post($request, array(
                'comment' => $comment->getId(),
                'forecast' => $forecast->getId()
            ));

            $data = $comment;

            $options = [
                'format' => $_format,
                'code' => Response::HTTP_CREATED
            ];

        }
        catch(\Exception $e) {
            $options = $this->getExceptionOptions($e, $_format);
        }

        return $this->apiResponse($data, $options);

    }

}