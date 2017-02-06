<?php

namespace Tavro\Bundle\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiNotFoundException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiRequestLimitException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;

use Doctrine\Common\Inflector\Inflector;

use Litwicki\Common\Common;

class DefaultController extends Controller
{
    
    /**
     * @param $entity
     * @param $id
     *
     * @return mixed
     */
    protected function findOr404($entity, $id)
    {
        if (!($entity = $this->container->get('tavro.handler.' . $entity)->get($id))) {
            throw new ApiNotFoundException(sprintf('The resource \'%s\' was not found.', $id));
        }

        return $entity;
    }

    /**
     * Serialize an Entity or array of Entities.
     *
     * @param $data
     * @param string $format
     * @param string $group
     *
     * @return
     * @throws \Exception
     * @internal param $string $group
     *
     */
    public function serialize($data, $format = 'json', $group = 'api')
    {
        try {
            $serializer = $this->container->get('tavro_serializer');
            return $serializer->serialize($data, $format, $group);
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Fetch the proper handler for an entity by its route name.
     *
     * @param $entityName
     *
     * @return \Tavro\Bundle\CoreBundle\Handler\
     * @throws \Exception
     */
    public function getHandler($entityName)
    {
        try {
            $service = sprintf('tavro.handler.%s', $entityName);
            return $this->container->get($service);
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $raw
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function apiResponse($raw, array $options = array())
    {
        try {

            $format = isset($options['format']) ? $options['format'] : 'json';
            $group = isset($options['group']) ? $options['group'] : 'api';
            $code = isset($options['code']) ? $options['code'] : 200;
            $message = isset($options['message']) ? $options['message'] : '';

            $response = new Response();

            if($format == 'json') {
                $response->headers->set('Content-Type', 'application/json');
                $responseData = $this->serialize([
                    'message' => $message,
                    'data' => $raw,
                ], $format, $group);
            }
            else {
                $response->headers->set('Content-Type', 'application/xml');
                $responseData = [];
            }

            $response->setContent($responseData);
            $response->setStatusCode($code);

        }
        catch(\Exception $e) {
            throw $e;
        }

        return $response;
    }

}