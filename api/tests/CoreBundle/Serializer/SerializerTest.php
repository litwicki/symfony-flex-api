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
        $entity = $serializer->deserialize($json, 'Tavro\Bundle\CoreBundle\Entity\User', 'json');
        $this->assertTrue(($entity instanceof User));
    }

    public function testSerializeXml()
    {
        $user = new User();
        $serializer = $this->container->get('tavro_serializer');
        $xml = $serializer->serialize($user, 'xml');

        libxml_use_internal_errors( true );

        $doc = new \DOMDocument('1.0', 'utf-8');

        $doc->loadXML( $xml );

        $errors = libxml_get_errors();

        $this->assertEmpty($errors, 'There should be no XML errors serializing an Entity to XML.');

    }

    public function testDeserializeXml()
    {
        $user = new User();
        $serializer = $this->container->get('tavro_serializer');
        $xml = $serializer->serialize($user, 'xml');
        $entity = $serializer->deserialize($xml, 'Tavro\Bundle\CoreBundle\Entity\User', 'xml');
        $this->assertTrue(($entity instanceof User));
    }

}