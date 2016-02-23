<?php namespace Tests\CoreBundle\Controller;

use Guzzle\Http\Client;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DefaultControllerTest extends KernelTestCase
{

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testCommentPost()
    {

        $user = $this->em->getRepository('TavroCoreBundle:User')->findOneBy(array('email' => 'user1@tavro.dev'));

        $headers = array(
            'X-AUTH-TOKEN' => $user->getApiKey()
        );

        $host = 'http://api.tavro.dev';

        // create our http client (Guzzle)
        $client = new Client($host, array(
            'request.options' => array(
                'exceptions' => false,
                //'auth' => array($user->getApiKey(), $user->getApiPassword(), 'Basic'),
                'headers' => $headers
            )
        ));

        $data = array(
            'user' => $user->getId(),
            'title' => 'PHPUnit Comment Title',
            'body' => 'PHPUnit Comment Body'
        );

        $request = $client->post('/api/v1/nodes', null, json_encode($data));
        $response = $request->send();
        $data = json_decode($response->getBody(), true);

        dump($data);die();

    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
    }

}