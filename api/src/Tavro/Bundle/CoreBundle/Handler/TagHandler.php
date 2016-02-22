<?php

namespace Tavro\Bundle\CoreBundle\Handler;

use Tavro\Bundle\CoreBundle\Services\Api\EntityHandler;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;

/**
 * Class TagHandler
 *
 * @package Tavro\Bundle\CoreBundle\Handler
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

                $sql = 'SELECT t FROM TavroCoreBundle:Tag t WHERE t.title LIKE :title';
                $query = $em->createQuery($sql);

                $query->setParameter('title', '%' . $parameters['title'] . '%');

                $entities = $query->getResult();

            }
            else {
                $entities = $this->repository->findAll();
            }

            return $entities;

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