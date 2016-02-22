<?php

namespace Tavro\Bundle\CoreBundle\Controller\Api;

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

use Litwicki\Common\Common;
use Tavro\Bundle\CoreBundle\Controller\Api\DefaultController as ApiController;

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
        try {

            $entities = $expense->getExpenseComments();

            $items = array();

            foreach($entities as $entity) {
                $items[] = $entity->getComment();
            }

        }
        catch(\Exception $e) {
            throw $e;
        }
        finally {
            $data = $this->serialize($items, $_format, $group = 'simple');
            $response = $this->apiResponse($data, $_format);
            return $response;
        }
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
        try {

            $data = json_decode($request->getContent(), true);

            $handler = $this->getHandler('comments');
            $comment = $handler->post($request, $data);

            /**
             * Attach the Comment to the Expense
             */
            $this->getHandler('expense_comments')->post($request, array(
                'comment' => $comment->getId(),
                'expense' => $expense->getId()
            ));

        }
        catch(\Exception $e) {
            throw $e;
        }
        finally {
            $routeOptions = array(
                'entity'  => 'comment',
                'id'      => $comment->getId(),
                'format'  => $_format,
            );

            return $this->forward('TavroCoreBundle:Default:get', $routeOptions);
        }
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
        try {

            $entities = $expense->getExpenseTags();

            $items = array();

            foreach($entities as $entity) {
                $items[] = $entity->getTag();
            }

        }
        catch(\Exception $e) {
            throw $e;
        }
        finally {
            $data = $this->serialize($items, $_format, $group = 'simple');
            $response = $this->apiResponse($data, $_format);
            return $response;
        }
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
        try {

            $data = json_decode($request->getContent(), true);

            $handler = $this->getHandler('tags');
            $tag = $handler->post($request, $data);

            /**
             * Attach the Comment to the Expense
             */
            $this->getHandler('expense_tags')->post($request, array(
                'comment' => $tag->getId(),
                'expense' => $expense->getId()
            ));

        }
        catch(\Exception $e) {
            throw $e;
        }
        finally {
            $routeOptions = array(
                'entity'  => 'comment',
                'id'      => $tag->getId(),
                'format'  => $_format,
            );

            return $this->forward('TavroCoreBundle:Default:get', $routeOptions);
        }
    }

}