<?php namespace Tests\ApiBundle\Controller;

use GuzzleHttp\Client;;
use Tests\ApiBundle\TavroApiTest;

class ShareholderTest extends TavroApiTest
{

    public function testShareholderRoute()
    {
        $client = new Client('/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $client = $this->authorize($this->getApiClient());;

        $url = '/api/v1/shareholders';

        $request = $client->get($url, null, ['verify' => false]);
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testShareholderCreate()
    {

        $client = $this->authorize($this->getApiClient());;
        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'person' => 1,
            'body' => $faker->text(500),
        );

        $url = '/api/v1/shareholders';

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