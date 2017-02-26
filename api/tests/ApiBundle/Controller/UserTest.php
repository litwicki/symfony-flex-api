<?php namespace Tests\ApiBundle\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Response;
use Tests\ApiBundle\TavroApiTest;
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
        $client = $this->authorize($this->getApiClient());

        $faker = \Faker\Factory::create('en_EN');

        $email = $faker->safeEmail;

        $data = array(
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => $email,
            'username' => $faker->userName,
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