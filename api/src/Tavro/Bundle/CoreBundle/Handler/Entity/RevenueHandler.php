<?php

namespace Tavro\Bundle\CoreBundle\Handler\Entity;

use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Model\EntityHandler;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\UsernameNotUniqueException;
use Tavro\Bundle\CoreBundle\Exception\EmailNotUniqueException;

use Rhumsaa\Uuid\Uuid;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Tavro\Bundle\CoreBundle\Exception\InvalidUsernameException;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\HttpFoundation\Request;
use Tavro\Bundle\CoreBundle\Entity\Revenue;
use Tavro\Bundle\CoreBundle\Entity\RevenueService;
use Tavro\Bundle\CoreBundle\Entity\RevenueProduct;
use Tavro\Bundle\CoreBundle\Entity\RevenueCategory;

/**
 * Class RevenueHandler
 *
 * @package Tavro\Bundle\CoreBundle\Handler
 */
class RevenueHandler extends EntityHandler
{

    /**
     * Find all Entities (limit the response size)
     * Optionally page the result set by LIMIT and OFFSET.
     *
     * @param array $params
     *
     * @throws \Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException
     * @throws \Exception
     * @return array|void
     */
    public function findAll(array $params = array())
    {
        try {

            $organizations = $this->getMyOrganizations();

            $page = isset($params['page']) ? $params['page'] : 1;
            $size = isset($params['size']) ? $params['size'] : self::PAGE_SIZE;

            $sort = (isset($params['sort'])) ? $params['sort'] : 'desc';
            $orderBy = (isset($params['orderBy'])) ? $params['orderBy'] : 'views';

            $sortOrder = array($orderBy => $sort);

            $offset = ($page - 1) * $size;

            $params = $this->filterParams($params);

            //default the status to ACTIVE
            if(!isset($params['status'])) {
                $params['status'] = self::STATUS_ACTIVE;
            }

            $entities = $this->repository->findAllByOrganization($organizations, $size, $offset, $params);

            $items = array();
            $count = 0;

            foreach($entities as $entity) {
                if($this->auth->isGranted('view', $entity)) {
                    $id = $entity->getOrganization()->getId();
                    $items[$id][] = $entity;
                    $count++;
                }
            }

            return array(
                'data' => $items,
                'message' => sprintf('%s Revenues retrieved.', $count),
            );

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * Create a new Entity.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $parameters
     *
     * @return object|\Tavro\Bundle\CoreBundle\Model\EntityInterface|void
     */
    public function create(Request $request, array $parameters)
    {
        try {

            if(!isset($parameters['status'])) {
                $parameters['status'] = $this::STATUS_ACTIVE;
            }

            $entity = $this->createEntity();
            $this->validate($entity, $parameters);
            $entity = $this->processForm($request, $entity, $parameters);

            /**
             * If this is an ApiEntity immediately save so the slug property
             * is updated correctly with the entity Id: {id}-{url-save-title}
             */
            if($entity instanceof EntityInterface) {
                return $this->patch($request, $entity, $parameters, self::HTTP_METHOD_PATCH);
            }

            return $entity;

        }
        catch(ApiAccessDeniedException $e) {
            throw new ApiAccessDeniedException($this::ACCESS_DENIED_MESSAGE);
        }
        catch(TransformationFailedException $e) {
            throw $e;
        }
        catch(UnexpectedTypeException $e) {
            throw $e;
        }
        catch(InvalidPropertyPathException $e) {
            throw $e;
        }
        catch(\Symfony\Component\Security\Core\Exception\AccessDeniedException $e) {
            throw new ApiAccessDeniedException($this::ACCESS_DENIED_MESSAGE);
        }
    }

    public function setRevenueServices(Revenue $revenue, array $services)
    {

        $items = [];

        if(empty($services)) {
            return;
        }

        /**
         * Remove all Services so we can add the new batch.
         */
        $this->removeServices($revenue);

        foreach($services as $id) {
            $items[] = $this->om->getRepository('TavroCoreBundle:RevenueService')->find($id);
        }

        foreach($items as $service) {
            $revenue->addRevenueService($service);
        }

        $this->om->persist($revenue);
        $this->om->flush();

    }

    public function setRevenueProducts(Revenue $revenue, array $products)
    {

        $items = [];

        if(empty($products)) {
            return;
        }

        /**
         * Remove all Services so we can add the new batch.
         */
        $this->removeProducts($products);

        foreach($products as $id) {
            $items[] = $this->om->getRepository('TavroCoreBundle:RevenueProduct')->find($id);
        }

        foreach($items as $product) {
            $revenue->addRevenueProduct($product);
        }

        $this->om->persist($revenue);
        $this->om->flush();

    }

    /**
     * Remove All Services from a Revenue record.
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Revenue $revenue
     *
     * @throws \Exception
     */
    public function removeServices(Revenue $revenue)
    {
        try {

            foreach($revenue->getRevenueServices() as $rs) {
                $revenue->removeRevenueService($rs);
            }

            $this->om->persist($revenue);
            $this->om->flush();

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Remove All Products from a Revenue record.
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Revenue $revenue
     *
     * @throws \Exception
     */
    public function removeProducts(Revenue $revenue)
    {
        try {

            foreach($revenue->getRevenueProducts() as $rp) {
                $revenue->removeRevenueProduct($rp);
            }

            $this->om->persist($revenue);
            $this->om->flush();

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

}