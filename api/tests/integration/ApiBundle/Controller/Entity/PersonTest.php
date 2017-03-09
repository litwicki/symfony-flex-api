<?php namespace Tests\ApiBundle\Controller\Entity;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Response;
use Tests\ApiBundle\TavroApiTest;

class PersonTest extends TavroApiTest
{

    public function testPersonRoute()
    {

        $client = $this->authorize($this->getApiClient());;

        $url = '/api/v1/people';

        $response = $client->get($url);

        $json = $response->getBody(true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    public function testPersonCreate()
    {
        $client = $this->authorize($this->getApiClient());

        $faker = \Faker\Factory::create('en_EN');
        $genders = array('male', 'female');

        $gender = $genders[rand(0,1)];

        $data = [
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'body' => $faker->text(500),
            'email' => $faker->email,
            'gender' => $gender,
            'phone' => '555-867-5309',
            'address' => $faker->address,
            'city' => $faker->city,
            'state' => $faker->state,
            'zip' => $faker->postcode,
        ];

        $url = '/api/v1/people';

        $response = $client->post($url, [
            'json' => $data
        ]);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

    }

    public function testPersonCreateBadGender()
    {
        try {

            $client = $this->authorize($this->getApiClient());;

            $faker = \Faker\Factory::create('en_EN');

            $data = [
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'body' => $faker->text(500),
                'email' => $faker->email,
                'gender' => 'gremlin',
                'phone' => '555-867-5309',
                'address' => $faker->address,
                'city' => $faker->city,
                'state' => $faker->state,
                'zip' => $faker->postcode,
            ];

            $url = '/api/v1/people';


            $response = $client->post($url, [
                'json' => $data
            ]);

        }
        catch(RequestException $e) {
            $this->assertEquals(Response::HTTP_BAD_REQUEST, $e->getResponse()->getStatusCode());
            $this->assertEquals(1, preg_match('/Please enter a valid gender/', $e->getMessage()));
        }

    }

}