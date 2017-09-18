<?php namespace Tests\Integration\ApiBundle\Controller\AccountEntity;

use GuzzleHttp\Client;
use Tests\TavroApiTest;
use Symfony\Component\HttpFoundation\Response;

class AccountGroupTest extends TavroApiTest
{

    public function testAccountGroupRoute()
    {
        $client = $this->authorize($this->getApiClient());

        $url = '/api/v1/accounts/1/groups';

        $response = $client->get($url);

        $json = $response->getBody();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    public function testAccountGroupCreate()
    {

        $client = $this->authorize($this->getApiClient());

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'name' => $faker->company,
            'body' => $faker->text(rand(100,1000)),
            'account' => 1,
            'user' => 1
        );

        $url = '/api/v1/accounts/1/groups';

        $response = $client->post($url, [
            'json' => $data
        ]);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

    }

}