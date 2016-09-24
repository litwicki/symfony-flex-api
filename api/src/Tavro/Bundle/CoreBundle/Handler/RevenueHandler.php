<?php

namespace Tavro\Bundle\CoreBundle\Handler;

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

use Tavro\Bundle\CoreBundle\Component\Form\FormErrors;

use Tavro\Bundle\CoreBundle\Model\AccountEntityHandler;

/**
 * Class RevenueHandler
 *
 * @package Tavro\Bundle\CoreBundle\Handler
 */
class RevenueHandler extends AccountEntityHandler
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $entity
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

            $this->validate($entity, $parameters);

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
            $items[] = $this->om->getRepository('TavroCoreBundle:RevenueService')->find($id);
        }

        foreach($items as $service) {
            $revenue->addRevenueService($service);
        }

        $this->om->persist($revenue);
        $this->om->flush();

    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\Revenue $revenue
     * @param array $products
     */
    public function setRevenueProducts(Revenue $revenue, array $products)
    {

        $items = [];

        if(empty($products)) {
            return;
        }

        /**
         * Remove all Services so we can add the new batch.
         */
        $this->removeProducts($revenue);

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