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

use App\Entity\Account;
use App\Entity\Expense;
use App\Entity\ExpenseComment;
use Symfony\Component\HttpFoundation\Cookie;

use App\Entity\FundingRound;
use Litwicki\Common\Common;
use App\Controller\Api\AccountEntityApiController;

class FundingController extends AccountEntityApiController
{
    /**
     * Display all Comments for this Node.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\FundingRound $funding_round
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function commentsAction(Request $request, FundingRound $funding_round, $_format)
    {
        $data = null;

        try {

            $entities = $funding_round->getFundingRoundComments();

            $data = array();

            foreach($entities as $entity) {
                $data[] = $entity->getComment();
            }

            $options = [
                'code' => Response::HTTP_OK,
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
     * @param \App\Entity\FundingRound $funding_round
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function newCommentAction(Request $request, FundingRound $funding_round, $_format)
    {
        $data = null;

        try {

            $data = $this->getPayload($request);

            $handler = $this->getHandler('comments');
            $comment = $handler->post($request, $data);

            /**
             * Attach the Comment to the FundingRound
             */
            $this->getHandler('funding_round_comments')->post($request, array(
                'comment' => $comment->getId(),
                'funding_round' => $funding_round->getId()
            ));

            $data = $comment;

            $options = [
                'code' => Response::HTTP_CREATED,
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
     * Display all Comments for this Node.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\FundingRound $funding_round
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function shareholdersAction(Request $request, FundingRound $funding_round, $_format)
    {
        $data = null;

        try {

            $entities = $funding_round->getFundingRoundShareholders();

            $data = array();

            foreach($entities as $entity) {
                $data[] = $entity->getShareholder();
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
     * @param \App\Entity\FundingRound $funding_round
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function newShareholderAction(Request $request, FundingRound $funding_round, $_format)
    {
        $data = null;

        try {

            $data = $this->getPayload($request);

            $handler = $this->getHandler('shareholders');
            $shareholder = $handler->create(array_merge($data, array(
               'funding_round' => $funding_round,
            )));

            $data = $shareholder;

            $options = [
                'code' => Response::HTTP_CREATED,
                'format' => $_format
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
     * @param \App\Entity\Account $account
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function byAccountAction(Request $request, Account $account, $_format)
    {
        $data = null;

        try {

            $data = $account->getFundingRounds();

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