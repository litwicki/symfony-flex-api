<?php

namespace App\Controller\AccountEntity;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Exception\Api\ApiException;
use App\Exception\Api\ApiNotFoundException;
use App\Exception\Api\ApiRequestLimitException;
use App\Exception\Api\ApiAccessDeniedException;
use App\Exception\Form\InvalidFormException;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use App\Entity\Expense;
use App\Entity\ExpenseComment;
use Symfony\Component\HttpFoundation\Cookie;

use App\Entity\Revenue;
use App\Entity\Account;

use Litwicki\Common\Common;
use App\Controller\Api\AccountEntityApiController;

class RevenueController extends AccountEntityApiController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Revenue $revenue
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function newCommentAction(Request $request, Revenue $revenue, $_format)
    {
        $data = null;

        try {

            $data = $this->getPayload($request);

            $handler = $this->getHandler('comments');
            $comment = $handler->post($request, $data);

            /**
             * Attach the Comment to the Revenue
             */
            $this->getHandler('revenue_comments')->post($request, array(
                'comment' => $comment->getId(),
                'revenue' => $revenue->getId()
            ));

            $data = $comment;

            $options = [
                'format' => $_format,
                'code' => Response::HTTP_CREATED,
                'message' => sprintf('Comment %s submitted to Revenue %s', $comment->getId(), $revenue->getId())
            ];

        }
        catch(\Exception $e) {
            $options = $this->getExceptionOptions($e, $_format);
        }

        return $this->apiResponse($data, $options);
    }

    /**
     * Display all Comments for this Expense.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Expense $revenue
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function commentsAction(Request $request, Expense $revenue, $_format)
    {
        $data = null;

        try {

            $handler = $this->getHandler('revenues');
            $data = $handler->getComments($revenue);

            $options = [
                'format' => $_format,
                'group' => 'simple'
            ];

        }
        catch(\Exception $e) {
            $options = [
                'format' => $_format,
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }

        return $this->apiResponse($data, $options);

    }
}