<?php

namespace Tavro\Bundle\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiNotFoundException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiRequestLimitException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Tavro\Bundle\CoreBundle\Entity\Expense;
use Tavro\Bundle\CoreBundle\Entity\ExpenseComment;
use Symfony\Component\HttpFoundation\Cookie;

use Tavro\Bundle\CoreBundle\Entity\Revenue;

use Litwicki\Common\Common;
use Tavro\Bundle\ApiBundle\Controller\ApiController as ApiController;

class RevenueController extends ApiController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Revenue $revenue
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function newCommentAction(Request $request, Revenue $revenue, $_format)
    {
        try {

            $data = json_decode($request->getContent(), TRUE);

            $handler = $this->getHandler('comments');
            $comment = $handler->post($request, $data);

            /**
             * Attach the Comment to the Revenue
             */
            $this->getHandler('revenue_comments')->post($request, array(
                'comment' => $comment->getId(),
                'revenue' => $revenue->getId()
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
}