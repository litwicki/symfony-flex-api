<?php namespace Tavro\Tests\Api\Controller;

use Guzzle\Http\Client;
use Tavro\Bundle\CoreBundle\Testing\TavroTest;

class ProductTest extends TavroTest
{

    public function testProductRoute()
    {
        $client = new Client('https://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $token = $this->authorize();

        $url = 'https://api.tavro.dev/api/v1/products';

        $request = $client->get($url, null, ['verify' => false]);
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testProductCreate()
    {
        $faker = \Faker\Factory::create('en_EN');

        $token = $this->authorize();

        $data = array(
            'name' => $faker->text(rand(10,100)),
            'body' => $faker->text(rand(100,1000)),
            'price' => 100,
            'cost' => 75,
            'category' => 1,
            'account' => 1
        );

        $url = 'https://api.tavro.dev/api/v1/products';

        $client = new Client($url, array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $request = $client->post($url, null, json_encode($data), ['verify' => false]);
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());

    }

}