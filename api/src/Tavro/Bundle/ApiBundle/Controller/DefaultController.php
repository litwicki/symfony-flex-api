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
use Tavro\Bundle\ApiBundle\Controller\ApiController;

class DefaultController extends ApiController
{

    /**
     * Convert object to array.
     *
     * @param $object
     * @param string $format
     * @param string $group
     *
     * @return mixed
     */
    public function toArray($object, $format = 'json', $group = 'api')
    {

        switch($format) {
            case 'xml':
                $string = $this->get('tavro_serializer')->serialize($object, $format, $group);
                return json_decode(json_encode((array) simplexml_load_string($string)), TRUE);
                break;
            case 'json':
            default:
                return json_decode($this->get('tavro_serializer')->serialize($object, $format, $group), TRUE);
                break;
        }

    }

    /**
     * Convert SimpleXMLObject to an Array.
     * @see: http://www.php.net/manual/en/ref.simplexml.php#111227
     *
     * @param $xmlObject
     * @param array $out
     *
     * @return array
     */
    public function xml2array($xmlObject, $out = array())
    {
        foreach ( (array) $xmlObject as $index => $node ) {
            $out[$index] = (is_object($node) || is_array($node)) ? $this->xml2array($node) : $node;
        }

        return $out;
    }

    /**
     * Convert an array to an XML object.
     *
     * @param \SimpleXMLElement $object
     * @param array $data
     *
     * @return \SimpleXMLElement
     */
    public function toXml(\SimpleXMLElement $object, array $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $new_object = $object->addChild($key);
                $this->toXml($new_object, $value);
            }
            else {
                $object->addChild($key, $value);
            }
        }
        return $object;
    }

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

}