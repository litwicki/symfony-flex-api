<?php namespace Tests\ApiBundle\Controller;

use GuzzleHttp\Client;
use Tests\ApiBundle\TavroApiTest;
use Symfony\Component\HttpFoundation\Response;

class AccountGroupTest extends TavroApiTest
{

    public function testAccountGroupRoute()
    {
        $client = $this->getApiClient();

        $token = $this->authorize();

        $url = '/api/v1/accounts/1/groups';

        $response = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $token)
            ]
        ]);

        $json = $response->getBody(true);
        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testAccountGroupCreate()
    {

        $token = $this->authorize();

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'name' => $faker->company,
            'body' => $faker->text(rand(100,1000)),
            'account' => 1,
            'user' => 1
        );

        $url = 'https://api.tavro.dev/api/v1/accounts/1/groups';

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

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

    }

}