<?php

namespace Tavro\Bundle\CoreBundle\Handler\AccountEntity;

use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Handler\EntityHandler;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;
use Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface;
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
use Tavro\Bundle\CoreBundle\Entity\Product;
use Tavro\Bundle\CoreBundle\Entity\Service;
use Tavro\Bundle\CoreBundle\Entity\RevenueService;
use Tavro\Bundle\CoreBundle\Entity\RevenueProduct;
use Tavro\Bundle\CoreBundle\Entity\RevenueCategory;

use Tavro\Bundle\CoreBundle\Component\Form\FormErrors;

use Tavro\Bundle\CoreBundle\Handler\AccountEntityHandler;
use Tavro\Bundle\CoreBundle\Model\HandlerInterface\CommentEntityHandlerInterface;
use Tavro\Bundle\CoreBundle\Model\HandlerInterface\TagEntityHandlerInterface;

/**
 * Class RevenueHandler
 *
 * @package Tavro\Bundle\CoreBundle\Handler\Entity
 */
class RevenueHandler extends AccountEntityHandler implements CommentEntityHandlerInterface
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface $entity
     * @param array $parameters
     * @param string $method
     *
     * @throws \Exception
     * @throws \Symfony\Component\Debug\Exception\ContextErrorException
     */
    public function processForm(Request $request, EntityInterface $entity, array $parameters, $method = self::HTTP_METHOD_POST)
    {
        try {

            $services = [];
            $products = [];

            if(isset($parameters['services'])) {
                $services = $parameters['services'];
                unset($parameters['services']);
            }

            if(isset($parameters['products'])) {
                $products = $parameters['products'];
                unset($parameters['products']);
            }

            $formType = $this->mapEntityToForm($this->entityClass);

            $form = $this->formFactory->create($formType, $entity, ['method' => $method]);

            /**
             * @reference: http://symfony.com/doc/current/form/direct_submit.html
             *           docs say this is required, but wtf?
             *
             *           $form->handleRequest($request);
             *
             */

            $form->submit($parameters, ($method == 'PATCH' ? false : true));

            if ($form->isValid()) {

                $entity = $form->getData();
                $class = new \ReflectionClass($entity);

                switch($method) {

                    case 'POST':
                        if(!($this->auth->isGranted('create', $entity))) {
                            $message = sprintf('You are not authorized to create a new %s.', $class->getShortName());
                            throw new ApiAccessDeniedException($message);
                        }
                        break;

                    case 'PUT':
                        if(!($this->auth->isGranted('edit', $entity))) {
                            $message = sprintf('You are not authorized to edit %s "%s"', $class->getShortName(), $entity->__toString());
                            throw new ApiAccessDeniedException($message);
                        }
                        break;

                }

                $this->om->persist($entity);
                $this->om->flush();

                if(!empty($products)) {
                    $this->setRevenueProducts($entity, $products);
                }

                if(!empty($services)) {
                    $this->setRevenueServices($entity, $services);
                }

                return $entity;

            }
            else {
                $formErrors = new FormErrors();
                $errors = $formErrors->getArray($form);
                $exception = $formErrors->getErrorsAsString($errors);
                throw new InvalidFormException($exception);
            }

        }
        catch(TransformationFailedException $e) {
            throw $e;
        }
        catch(ContextErrorException $e) {
            throw $e;
        }
        catch(UnexpectedTypeException $e) {
            throw $e;
        }
        catch(InvalidPropertyPathException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }



    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\Revenue $revenue
     * @param array $services
     */
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
            $items[] = $this->om->getRepository('TavroCoreBundle:Service')->find($id);
        }

        foreach($items as $service) {
            $entity = $this->newRevenueService($revenue, $service);
            $revenue->addRevenueService($entity);
        }

        $this->om->persist($revenue);
        $this->om->flush();

    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\Revenue $revenue
     * @param array $products
     *
     * @throws \Exception
     */
    public function setRevenueProducts(Revenue $revenue, array $products = array())
    {

        $items = [];
        $invalids = [];

        if(empty($products)) {
            return;
        }

        /**
         * Remove all Products so we can add the new batch.
         */
        $this->removeProducts($revenue);

        foreach($products as $id) {

            try {
                $items[] = $this->om->getRepository('TavroCoreBundle:Product')->find($id);
            }
            catch(\Exception $e) {
                $invalids[] = $id;
            }

        }

        if(!empty($invalids)) {
            throw new \Exception(sprintf('No Product found with Id %s', implode($invalids, ',')));
        }

        foreach($items as $product) {

            if(false === ($product->getAccount()->getId() === $revenue->getAccount()->getId())) {
                throw new ApiAccessDeniedException('Product/Revenue account mismatch!');
            }

            $entity = $this->newRevenueProduct($revenue, $product);
            $revenue->addRevenueProduct($entity);

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

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\Revenue $revenue
     * @param \Tavro\Bundle\CoreBundle\Entity\Product $product
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\RevenueProduct
     */
    public function newRevenueProduct(Revenue $revenue, Product $product)
    {
        $entity = new RevenueProduct();
        $entity->setRevenue($revenue);
        $entity->setProduct($product);
        $this->om->persist($entity);
        $this->om->flush();

        /**
         * @TODO: dispatch an event
         */

        return $entity;
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\Revenue $revenue
     * @param \Tavro\Bundle\CoreBundle\Entity\Service $service
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\RevenueService
     */
    public function newRevenueService(Revenue $revenue, Service $service)
    {
        $entity = new RevenueService();
        $entity->setRevenue($revenue);
        $entity->setService($service);
        $this->om->persist($entity);
        $this->om->flush();

        /**
         * @TODO: dispatch an event
         */
        return $entity;
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface $revenue
     *
     * @return array
     * @throws \Exception
     */
    public function getComments(EntityInterface $revenue)
    {

        $comments = array();

        if(!$revenue instanceof Revenue) {
            throw new \Exception(sprintf('Cannot fetch comments for Revenue from %s', get_class($revenue)));
        }

        $entities = $revenue->getRevenueComments();

        if(!empty($entities)) {
            foreach($entities as $entity) {
                $comments[$entity->getId()] = $entity->getComment();
            }
        }

        return $comments;

    }

}