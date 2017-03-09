<?php namespace Tests\Integration\ApiBundle\Controller\Entity;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Response;
use Tests\Integration\ApiBundle\TavroApiTest;
use Rhumsaa\Uuid\Uuid;

class UserTest extends TavroApiTest
{

    public function testUserRoute()
    {
        $client = $this->authorize($this->getApiClient());

        $url = '/api/v1/users';

        $response = $client->get($url);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    public function testUserCreate()
    {
        $client = new Client([
            'verify' => false,
            'base_uri' => 'http://api.tavro.dev',
            'request.options' => [
                'exceptions' => FALSE
            ],
            'headers' => [
                'Content-Type' => 'application/json'
            ],
        ]);

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'person' => [
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->safeEmail,
            ],
            'username' => preg_replace('/[^A-Za-z0-9-_]/', '', $faker->userName),
            'signature' => $faker->text(100),
            'password' => 'Password1!'
        );

        $url = '/api/v1/signup';

        $response = $client->post($url, [
            'json' => $data
        ]);

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

    }

}