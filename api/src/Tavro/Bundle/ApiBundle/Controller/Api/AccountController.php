<?php

namespace Tavro\Bundle\ApiBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiNotFoundException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiRequestLimitException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;
use Tavro\Bundle\CoreBundle\Exception\Entity\Account\AccountStatusDisabledException;
use Tavro\Bundle\CoreBundle\Exception\Entity\Account\AccountStatusPendingException;
use Tavro\Bundle\CoreBundle\Exception\Entity\Account\AccountStatusOtherException;
use Tavro\Bundle\CoreBundle\Exception\Entity\Account\AccountStatusInvalidException;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Tavro\Bundle\CoreBundle\Entity\Expense;
use Tavro\Bundle\CoreBundle\Entity\ExpenseComment;
use Symfony\Component\HttpFoundation\Cookie;
use Tavro\Bundle\CoreBundle\Entity\Account;

use Litwicki\Common\Common;
use Tavro\Bundle\ApiBundle\Controller\Api\ApiController as ApiController;

class AccountController extends ApiController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function productsAction(Request $request, Account $account, $_format)
    {
        try {

            $entities = $account->getProducts();

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
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function productCategoriesAction(Request $request, Account $account, $_format)
    {
        try {

            $entities = $account->getProductCategories();

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
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function revenuesAction(Request $request, Account $account, $_format)
    {
        try {

            $entities = $account->getRevenues();

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
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function revenueCategoriesAction(Request $request, Account $account, $_format)
    {
        try {

            $entities = $account->getRevenueCategories();

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
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function expensesAction(Request $request, Account $account, $_format)
    {
        try {

            $entities = $account->getExpenses();

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
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function expenseCategoriesAction(Request $request, Account $account, $_format)
    {
        try {

            $entities = $account->getExpenseCategories();

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
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function nodesAction(Request $request, Account $account, $_format)
    {
        try {

            $entities = $account->getNodes();

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
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function fundingAction(Request $request, Account $account, $_format)
    {
        try {

            $entities = $account->getFundingRounds();

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
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function shareholdersAction(Request $request, Account $account, $_format)
    {
        try {

            $entities = $account->getShareholders();

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
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function organizationsAction(Request $request, Account $account, $_format)
    {
        try {

            if(false === $this->isGranted('view', $account)) {
                throw new ApiAccessDeniedException('You are not authorized to access this Account.');
            }

            $this->validateAccountStatus($account);

            $entities = $account->getOrganizations();

            return $this->apiResponse($entities, [
                'format' => $_format,
                'group' => 'simple'
            ]);
        }
        catch(\Exception $e) {
            throw $e;
        }
    }
}
