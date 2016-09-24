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
use Tavro\Bundle\CoreBundle\Entity\Account;

use Litwicki\Common\Common;
use Tavro\Bundle\ApiBundle\Controller\DefaultController as ApiController;

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
    public function usersAction(Request $request, Account $account, $_format)
    {
        try {

            $entities = $account->getAccountUsers();

            $items = array();

            foreach($entities as $entity) {
                $items[] = $entity->getUser();
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
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function groupsAction(Request $request, Account $account, $_format)
    {
        try {

            $entities = $account->getAccountGroups();

            $items = array();

            foreach($entities as $entity) {
                $items[] = $entity->getAccountGroup();
            }

            $data = $this->serialize($items, $_format);
            $response = $this->apiResponse($data, $_format);
            return $response;

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
    public function newUserAction(Request $request, Account $account, $_format)
    {
        try {

            $data = json_decode($request->getContent(), TRUE);

            $handler = $this->getHandler('users');
            $user = $handler->post($request, $data);

            /**
             * Attach the Comment to the Node
             */
            $this->getHandler('account_users')->post($request, array(
                'user' => $user->getId(),
                'account' => $account->getId()
            ));

        }
        catch(\Exception $e) {
            throw $e;
        }
        finally {
            $routeOptions = array(
                'entity'  => 'comments',
                'id'      => $user->getId(),
                'format'  => $_format,
            );

            return $this->forward('TavroApiBundle:Default:get', $routeOptions);
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
    public function newGroupAction(Request $request, Account $account, $_format)
    {
        try {

            $data = json_decode($request->getContent(), TRUE);

            $handler = $this->getHandler('account_groups');
            $entity = $handler->post($request, $data);

            $routeOptions = array(
                'entity'  => 'account_groups',
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
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function servicesAction(Request $request, Account $account, $_format)
    {
        try {
            $entities = $account->getServices();
        }
        catch(\Exception $e) {
            throw $e;
        }
        finally {
            $data = $this->serialize($entities, $_format, $group = 'simple');
            $response = $this->apiResponse($data, $_format);
            return $response;
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
    public function serviceCategoriesAction(Request $request, Account $account, $_format)
    {
        try {
            $entities = $account->getServiceCategories();
        }
        catch(\Exception $e) {
            throw $e;
        }
        finally {
            $data = $this->serialize($entities, $_format, $group = 'simple');
            $response = $this->apiResponse($data, $_format);
            return $response;
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
    public function productsAction(Request $request, Account $account, $_format)
    {
        try {
            $entities = $account->getProducts();
        }
        catch(\Exception $e) {
            throw $e;
        }
        finally {
            $data = $this->serialize($entities, $_format, $group = 'simple');
            $response = $this->apiResponse($data, $_format);
            return $response;
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
        }
        catch(\Exception $e) {
            throw $e;
        }
        finally {
            $data = $this->serialize($entities, $_format, $group = 'simple');
            $response = $this->apiResponse($data, $_format);
            return $response;
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
        }
        catch(\Exception $e) {
            throw $e;
        }
        finally {
            $data = $this->serialize($entities, $_format, $group = 'simple');
            $response = $this->apiResponse($data, $_format);
            return $response;
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
        }
        catch(\Exception $e) {
            throw $e;
        }
        finally {
            $data = $this->serialize($entities, $_format, $group = 'simple');
            $response = $this->apiResponse($data, $_format);
            return $response;
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
        }
        catch(\Exception $e) {
            throw $e;
        }
        finally {
            $data = $this->serialize($entities, $_format, $group = 'simple');
            $response = $this->apiResponse($data, $_format);
            return $response;
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
        }
        catch(\Exception $e) {
            throw $e;
        }
        finally {
            $data = $this->serialize($entities, $_format, $group = 'simple');
            $response = $this->apiResponse($data, $_format);
            return $response;
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
        }
        catch(\Exception $e) {
            throw $e;
        }
        finally {
            $data = $this->serialize($entities, $_format, $group = 'simple');
            $response = $this->apiResponse($data, $_format);
            return $response;
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
        }
        catch(\Exception $e) {
            throw $e;
        }
        finally {
            $data = $this->serialize($entities, $_format);
            $response = $this->apiResponse($data, $_format);
            return $response;
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
            $data = $this->serialize($entities, $_format);
            $response = $this->apiResponse($data, $_format);
            return $response;
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
            $entities = $account->getOrganizations();
        }
        catch(\Exception $e) {
            throw $e;
        }
        finally {
            $data = $this->serialize($entities, $_format, $group = 'simple');
            $response = $this->apiResponse($data, $_format);
            return $response;
        }
    }
}
