<?php namespace Tavro\Tests\Core\Serializer;

use Guzzle\Http\Client;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tavro\Bundle\CoreBundle\Entity\User;

class JsonSerializationVisitorTest extends KernelTestCase
{

    private $container;

    public function setUp()
    {
        self::bootKernel();
        $this->container = self::$kernel->getContainer();
    }

    /**
     * @group Core
     */
    public function testGetResult()
    {
        /**
         * @TODO: write this actual test..
         */
        $this->assertTrue(true);
    }
}