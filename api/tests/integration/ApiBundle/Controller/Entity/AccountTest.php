<?php namespace Tests\ApiBundle\Controller\Entity;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Response;
use Tests\ApiBundle\TavroApiTest;

class AccountTest extends TavroApiTest
{

    public function testAccountRoute()
    {
        $client = $this->authorize($this->getApiClient());

        $url = '/api/v1/accounts';

        $response = $client->get($url);

        $json = $response->getBody();
        $body = json_decode($json, true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    public function testAccountCreate()
    {

        $client = $this->authorize($this->getApiClient());

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'name' => $faker->company,
            'body' => $faker->text(rand(100,1000)),
            'user' => 1
        );

        $url = '/api/v1/accounts';

        $response = $client->post($url, [
            'json' => $data
        ]);

        $json = $response->getBody();
        $body = json_decode($json, true);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

    }

    public function testAccountCreateBadUser()
    {
        try {

            $client = $this->authorize($this->getApiClient());

            $faker = \Faker\Factory::create('en_EN');

            $data = array(
                'name' => $faker->company,
                'body' => $faker->text(rand(100,1000)),
                'user' => -1,
            );

            $url = '/api/v1/accounts';

            $response = $client->post($url, [
                'json' => $data
            ]);

            $json = $response->getBody();
            $body = json_decode($json, true);

        }
        catch(RequestException $e) {
            $this->assertEquals(Response::HTTP_BAD_REQUEST, $e->getResponse()->getStatusCode());
        }

    }

}