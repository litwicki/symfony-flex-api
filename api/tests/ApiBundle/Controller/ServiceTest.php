<?php namespace Tests\ApiBundle\Controller;

use GuzzleHttp\Client;;
use Tests\ApiBundle\TavroApiTest;

class ServiceTest extends TavroApiTest
{

    public function testServiceRoute()
    {
        $client = new Client('/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $client = $this->authorize($this->getApiClient());;

        $url = '/api/v1/services';

        $request = $client->get($url, null, ['verify' => false]);
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testServiceCreate()
    {

        $client = $this->authorize($this->getApiClient());;

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'type' => 'hourly',
            'name' => $faker->name,
            'body' => $faker->text(500),
            'price' => 100,
            'category' => 1,
            'account' => 1
        );

        $url = '/api/v1/services';

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

    public function testServiceCreateBadType()
    {

        $client = $this->authorize($this->getApiClient());;

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'type' => 'butts',
            'name' => $faker->name,
            'body' => $faker->text(500),
            'price' => 100,
            'category' => 1,
            'account' => 1
        );

        $url = '/api/v1/services';

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

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(1, preg_match('/Service type must match/', $body['message']));

    }

}