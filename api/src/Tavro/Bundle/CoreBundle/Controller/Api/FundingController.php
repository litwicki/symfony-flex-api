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

use Tavro\Bundle\CoreBundle\Entity\FundingRound;
use Litwicki\Common\Common;
use Tavro\Bundle\CoreBundle\Controller\Api\DefaultController as ApiController;

class FundingController extends ApiController
{
    /**
     * Display all Comments for this Node.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\FundingRound $funding_round
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function commentsAction(Request $request, FundingRound $funding_round, $_format)
    {
        try {

            $entities = $funding_round->getFundingRoundComments();

            $items = array();

            foreach($entities as $entity) {
                $items[] = $entity->getComment();
            }

            $data = $this->serialize($items, $_format, $group = 'simple');
            $response = $this->apiResponse($data, $_format);
            return $response;

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\FundingRound $funding_round
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function newCommentAction(Request $request, FundingRound $funding_round, $_format)
    {
        try {

            $data = json_decode($request->getContent(), true);

            $handler = $this->getHandler('comment');
            $comment = $handler->post($request, $data);

            /**
             * Attach the Comment to the FundingRound
             */
            $this->getHandler('funding_round_comment')->post($request, array(
                'comment' => $comment->getId(),
                'funding_round' => $funding_round->getId()
            ));

            $routeOptions = array(
                'entity'  => 'comment',
                'id'      => $comment->getId(),
                'format'  => $_format,
            );

            return $this->get($routeOptions);

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Display all Comments for this Node.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\FundingRound $funding_round
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function shareholdersAction(Request $request, FundingRound $funding_round, $_format)
    {
        try {

            $entities = $funding_round->getFundingRoundShareholders();

            $items = array();

            foreach($entities as $entity) {
                $items[] = $entity->getShareholder();
            }

            $data = $this->serialize($items, $_format, $group = 'simple');
            $response = $this->apiResponse($data, $_format);
            return $response;

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\FundingRound $funding_round
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function newShareholderAction(Request $request, FundingRound $funding_round, $_format)
    {
        try {

            $data = json_decode($request->getContent(), true);

            $handler = $this->getHandler('shareholder');
            $shareholder = $handler->create(array_merge($data, array(
               'funding_round' => $funding_round,
            )));

            $routeOptions = array(
                'entity'  => 'shareholder',
                'id'      => $shareholder->getId(),
                'format'  => $_format,
            );

            return $this->get($routeOptions);

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

}