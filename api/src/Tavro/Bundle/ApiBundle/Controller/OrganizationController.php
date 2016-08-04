<?php

namespace Tavro\Bundle\ApiBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiNotFoundException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiRequestLimitException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Tavro\Bundle\CoreBundle\Entity\Organization;
use Symfony\Component\HttpFoundation\Cookie;

use Litwicki\Common\Common;
use Tavro\Bundle\ApiBundle\Controller\DefaultController as ApiController;

class OrganizationController extends ApiController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function usersAction(Request $request, Organization $organization, $_format)
    {
        try {

            $entities = $organization->getUserOrganizations();

            $items = array();

            foreach($entities as $entity) {
                $items[] = $entity->getUser();
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
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function servicesAction(Request $request, Organization $organization, $_format)
    {
        try {
            $entities = $organization->getServices();
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
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function serviceCategoriesAction(Request $request, Organization $organization, $_format)
    {
        try {
            $entities = $organization->getServiceCategories();
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
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function productsAction(Request $request, Organization $organization, $_format)
    {
        try {
            $entities = $organization->getProducts();
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
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function productCategoriesAction(Request $request, Organization $organization, $_format)
    {
        try {
            $entities = $organization->getProductCategories();
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
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function revenuesAction(Request $request, Organization $organization, $_format)
    {
        try {
            $entities = $organization->getRevenues();
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
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function revenueCategoriesAction(Request $request, Organization $organization, $_format)
    {
        try {
            $entities = $organization->getRevenueCategories();
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
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function expensesAction(Request $request, Organization $organization, $_format)
    {
        try {
            $entities = $organization->getExpenses();
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
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function expenseCategoriesAction(Request $request, Organization $organization, $_format)
    {
        try {
            $entities = $organization->getExpenseCategories();
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
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function nodesAction(Request $request, Organization $organization, $_format)
    {
        try {
            $entities = $organization->getNodes();
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
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function fundingAction(Request $request, Organization $organization, $_format)
    {
        try {
            $entities = $organization->getFundingRounds();
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
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function shareholdersAction(Request $request, Organization $organization, $_format)
    {
        try {
            $items = array();
            $entities = $organization->getOrganizationShareholders();
            foreach($entities as $entity) {
                $items = $entity->getShareholder();
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
}