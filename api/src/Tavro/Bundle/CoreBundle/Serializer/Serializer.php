<?php

namespace Tavro\Bundle\CoreBundle\Serializer;

use JMS\Serializer\SerializationContext;

use Litwicki\Common\Common;

/**
 * Class Serializer
 *
 * @package Tavro\Bundle\CoreBundle\Services
 */
class Serializer
{

    private $serializer;

    /**
     * @param $serializer
     */
    public function __construct($serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Serialize "stuff"
     *
     * @param $data
     * @param $format
     * @param string $group
     *
     * @throws \Exception
     */
    public function serialize($data, $format, $group = 'api')
    {

        try {

        $string = $this->serializer->serialize(
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
     * @throws \Exception
     */
    public function deserialize($string, $format, $typeName)
    {
        try {
            return $this->serializer->deserialize($string, $typeName, $format);
        }
        catch(\Exception $e) {
            throw $e;
        }

    }
}
