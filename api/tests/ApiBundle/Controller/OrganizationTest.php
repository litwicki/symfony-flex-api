<?php namespace Tests\ApiBundle\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Response;
use Tests\ApiBundle\TavroApiTest;

class OrganizationTest extends TavroApiTest
{

    public function testOrganizationRoute()
    {
        $client = $this->authorize($this->getApiClient());

        $url = '/api/v1/organizations';

        $response = $client->get($url);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    public function testOrganizationCreate()
    {

        $faker = \Faker\Factory::create('en_EN');

        $client = $this->authorize($this->getApiClient());

        $data = array(
            'name' => $faker->name,
            'body' => $faker->text(rand(100,1000)),
            'address' => $faker->address,
            'city' => $faker->city,
            'state' => 'WA',
            'zip' => $faker->postcode,
            'website' => $faker->url,
            'phone' => '555-867-5309',
            'account' => 1
        );

        $url = '/api/v1/organizations';

        $response = $client->post($url, [
            'json' => $data
        ]);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

    }

    public function testOrganizationCreateBadAccount()
    {
        try {

            $client = $this->authorize($this->getApiClient());

            $faker = \Faker\Factory::create('en_EN');

            $data = array(
                'name' => $faker->name,
                'body' => $faker->text(rand(100,1000)),
                'address' => $faker->address,
                'city' => $faker->city,
                'state' => 'WA',
                'zip' => $faker->postcode,
                'website' => $faker->url,
                'phone' => '555-867-5309',
                'account' => -11
            );

            $url = '/api/v1/organizations';

            $client->post($url, [
                'json' => $data,
            ]);

        }
        catch(RequestException $e) {
            $this->assertEquals(Response::HTTP_BAD_REQUEST, $e->getResponse()->getStatusCode());
            $this->assertEquals(1, preg_match('/Please enter a valid Account/', $e->getMessage()));
        }

    }

}