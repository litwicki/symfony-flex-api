<?php namespace Tavro\Tests\Core\Serializer;

use GuzzleHttp\Client;;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tavro\Bundle\CoreBundle\Entity\User;

class SerializerTest extends KernelTestCase
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
    public function testSerialize()
    {
        $serializer = $this->container->get('serializer');
        $user = $this->container->get('doctrine')->getRepository('TavroCoreBundle:User')->findOneBy([
            'username' => 'tavrobot'
        ]);

        $string = $serializer->serialize(
            $user,
            'json',
            SerializationContext::create()
                ->setGroups(array('api'))
                ->setSerializeNull(TRUE)
                ->enableMaxDepthChecks()
        );

        $this->assertJson($string);

    }

    /**
     * @group Core
     */
    public function testSerializeDetail()
    {
        $serializer = $this->container->get('serializer');
        $user = $this->container->get('doctrine')->getRepository('TavroCoreBundle:User')->findOneBy([
            'username' => 'tavrobot'
        ]);

        $string = $serializer->serialize(
            $user,
            'json',
            SerializationContext::create()
                ->setGroups(array('detail'))
                ->setSerializeNull(TRUE)
                ->enableMaxDepthChecks()
        );

        $array = json_decode($string, TRUE);

        $this->assertArrayHasKey('last_online_date', $array);

    }

    /**
     * @group Core
     */
    public function testSerializeSimple()
    {
        $serializer = $this->container->get('serializer');
        $user = $this->container->get('doctrine')->getRepository('TavroCoreBundle:User')->findOneBy([
            'username' => 'tavrobot'
        ]);

        $string = $serializer->serialize(
            $user,
            'json',
            SerializationContext::create()
                ->setGroups(array('simple'))
                ->setSerializeNull(TRUE)
                ->enableMaxDepthChecks()
        );

        $array = json_decode($string, TRUE);

        $this->assertArrayNotHasKey('signature', $array);

    }

    /**
     * @group Core
     */
    public function testDeserialize()
    {
        $serializer = $this->container->get('serializer');
        $user = $this->container->get('doctrine')->getRepository('TavroCoreBundle:User')->findOneBy([
            'username' => 'tavrobot'
        ]);

        $string = $serializer->serialize(
            $user,
            'json',
            SerializationContext::create()
                ->setGroups(array('api'))
                ->setSerializeNull(TRUE)
                ->enableMaxDepthChecks()
        );

        $user = $serializer->deserialize($string, 'Tavro\Bundle\CoreBundle\Entity\User', 'json');

        $this->asserttrue($user instanceof User);
    }
}