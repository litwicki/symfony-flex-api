<?php namespace Tests\CoreBundle\Serializer;

use GuzzleHttp\Client;
use Tavro\Bundle\CoreBundle\Entity\User;
use Tests\CoreBundle\TavroCoreTest;
use Tests\SymfonyKernel;

class SerializerTest extends TavroCoreTest
{
    use SymfonyKernel;

    public function testSerializeJson()
    {
        $user = new User();
        $serializer = $this->container->get('tavro_serializer');
        $json = $serializer->serialize($user, 'json');
        $this->assertTrue((json_last_error() === JSON_ERROR_NONE));
    }

    public function testDeserializeJson()
    {
        $user = new User();
        $serializer = $this->container->get('tavro_serializer');
        $json = $serializer->serialize($user, 'json');
        $entity = $serializer->deserialize($json, 'Tavro\CoreBundle\Entity\User', 'json');
        $this->assertTrue(($entity instanceof User));
    }

    public function testSerializeXml()
    {
        $user = new User();
        $serializer = $this->container->get('tavro_serializer');
        $json = $serializer->serialize($user, 'xml');
        $this->assertTrue((json_last_error() === JSON_ERROR_NONE));
    }

    public function testDeserializeXml()
    {
        $user = new User();
        $serializer = $this->container->get('tavro_serializer');
        $json = $serializer->serialize($user, 'xml');
        $entity = $serializer->deserialize($json, 'Tavro\CoreBundle\Entity\User', 'json');
        $this->assertTrue(($entity instanceof User));
    }

}