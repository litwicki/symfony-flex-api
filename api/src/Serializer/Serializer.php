<?php

namespace App\Serializer;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer as JMS_Serializer;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;

/**
 * Class Serializer
 *
 * @package Tavro\Services
 */
class Serializer
{

    private $jms;
    
    public function __construct(SerializerInterface $jms)
    {
        $this->jms = $jms;
    }

    /**
     * Serialize "stuff"
     *
     * @param $data
     * @param $format
     * @param string $group
     *
     * @return
     * @throws \Exception
     */
    public function serialize($data, $format, $group = 'api')
    {

        try {

        $string = $this->jms->serialize(
            $data,
            $format,
            SerializationContext::create()
                ->setGroups(array($group))
                ->setSerializeNull(TRUE)
                ->enableMaxDepthChecks()
            );

            return $string;

        }
        catch(\Exception $e) {
            throw $e;
        }

    }

    /**
     * @param $string
     * @param $format
     * @param $typeName
     *
     * @return mixed
     * @throws \Exception
     */
    public function deserialize($string, $typeName, $format = 'json')
    {
        try {
            return $this->jms->deserialize($string, $typeName, $format);
        }
        catch(\Exception $e) {
            throw $e;
        }

    }
}
