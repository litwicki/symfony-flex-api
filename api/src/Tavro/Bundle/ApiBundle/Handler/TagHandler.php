<?php

namespace Tavro\Bundle\ApiBundle\Handler;

use Tavro\Bundle\ApiBundle\Services\EntityHandler;
use Tavro\Bundle\ApiBundle\Exception\ApiException;
use Tavro\Bundle\ApiBundle\Exception\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;

/**
 * Class TagHandler
 *
 * @package Tavro\Bundle\ApiBundle\Handler
 */
class TagHandler extends EntityHandler
{

    /**
     * @param array $parameters
     *
     * @return array
     * @throws \Exception
     */
    public function typeahead(array $parameters)
    {
        try {

            if(isset($parameters['title'])) {

                $em = $this->container->get('doctrine')->getManager();

                $query = $em->createQuery(
                    'SELECT t FROM TavroCoreBundle:Tag t WHERE t.title LIKE :title'
                );

                $query->setParameter('title', '%' . $parameters['title'] . '%');

                $entities = $query->getResult();

            }
            else {
                $entities = $this->repository->findAll();
            }

            return $entities;

//            $items = array();
//
//            foreach($entities as $entity) {
//                if($this->auth->isGranted('view', $entity)) {
//                    $items[] = $entity;
//                }
//            }
//
//            return $items;

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Filter out parameters we don't want to give access to.
     *
     * @param array $params
     *
     * @return array
     * @throws \Exception
     */
    public function filterParams(array $params)
    {
        try {

            $parameters = array();
            $options = array('status', 'title');

            foreach($params as $name => $value) {
                if(in_array($name, $options)) {
                    $parameters[$name] = $value;
                }
            }

            return $parameters;

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

}