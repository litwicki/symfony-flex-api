<?php

namespace Tavro\Bundle\ApiBundle\Controller\Entity;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiNotFoundException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiRequestLimitException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Tavro\Bundle\CoreBundle\Entity\Account;
use Tavro\Bundle\CoreBundle\Entity\Expense;
use Tavro\Bundle\CoreBundle\Entity\ExpenseComment;
use Symfony\Component\HttpFoundation\Cookie;

use Litwicki\Common\Common;
use Tavro\Bundle\ApiBundle\Controller\Api\ApiController as ApiController;

class ExpenseController extends ApiController
{

    /**
     * Display all Comments for this Expense.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Expense $expense
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function commentsAction(Request $request, Expense $expense, $_format)
    {
        $data = null;

        try {

            $entities = $expense->getExpenseComments();

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
            $options = [
                'format' => $_format,
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }

        return $this->apiResponse($data, $options);

    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Expense $expense
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function newCommentAction(Request $request, Expense $expense, $_format)
    {
        $data = null;

        try {

            $data = json_decode($request->getContent(), TRUE);

            $handler = $this->getHandler('comments');
            $comment = $handler->post($request, $data);

            /**
             * Attach the Comment to the Expense
             */
            $this->getHandler('expense_comments')->post($request, array(
                'comment' => $comment->getId(),
                'expense' => $expense->getId()
            ));

            $data = $comment;

            $options = [
                'format' => $_format,
                'code' => Response::HTTP_CREATED,
                'message' => sprintf('Comment %s submitted to Expense %s', $comment->getId(), $expense->getId())
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

    /**
     * Display all Tags for this Expense.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Expense $expense
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function tagsAction(Request $request, Expense $expense, $_format)
    {
        $data = null;

        try {

            $entities = $expense->getExpenseTags();

            $data = array();

            foreach($entities as $entity) {
                $data[] = $entity->getTag();
            }

            $options = [
                'format' => $_format,
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

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Expense $expense
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function newTagAction(Request $request, Expense $expense, $_format)
    {
        $data = null;

        try {

            $data = json_decode($request->getContent(), TRUE);

            $handler = $this->getHandler('tags');
            $tag = $handler->post($request, $data);

            /**
             * Attach the Comment to the Expense
             */
            $this->getHandler('expense_tags')->post($request, array(
                'tag' => $tag->getId(),
                'expense' => $expense->getId()
            ));

            $data = $tag;

            $options = [
                'format' => $_format,
                'code' => Response::HTTP_CREATED,
                'message' => sprintf('Tag %s submitted to Expense %s', $tag->getId(), $expense->getId())
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

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function byAccountAction(Request $request, Account $account, $_format)
    {
        $data = null;

        try {

            $data = $account->getExpenses();

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

}