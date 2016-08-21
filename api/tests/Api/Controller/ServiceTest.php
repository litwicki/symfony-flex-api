<?php namespace Tests\Api\Controller;

use Guzzle\Http\Client;
use Tavro\Bundle\CoreBundle\Testing\TavroTest;

class ServiceTest extends TavroTest
{

    public function testServiceRoute()
    {
        $client = new Client('http://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $token = $this->authorize();

        $url = 'http://api.tavro.dev/api/v1/services';

        $request = $client->get($url);
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testServiceCreate()
    {

        $token = $this->authorize();

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'type' => 'hour',
            'body' => $faker->text(500),
            'price' => 100,
            'status' => 1,
            'category' => 1,
            'organization' => 1
        );

        $url = 'http://api.tavro.dev/api/v1/services';

        $client = new Client($url, array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $request = $client->post($url, null, json_encode($data));
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testServiceCreateBadType()
    {

        $token = $this->authorize();

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'type' => 'butts',
            'body' => $faker->text(500),
            'price' => 100,
            'status' => 1,
            'category' => 1,
            'organization' => 1
        );

        $url = 'http://api.tavro.dev/api/v1/services';

        $client = new Client($url, array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $request = $client->post($url, null, json_encode($data));
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(1, preg_match('/Choose a valid service type./', $body['message']));

    }

}