<?php namespace Tests\ApiBundle\Controller\AccountEntity;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Response;
use Tests\ApiBundle\TavroApiTest;

class ServiceTest extends TavroApiTest
{

    public function testServiceRoute()
    {

        $client = $this->authorize($this->getApiClient());

        $url = '/api/v1/services';

        $response = $client->get($url);

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    public function testServiceCreate()
    {

        $client = $this->authorize($this->getApiClient());

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'name' => $faker->name,
            'body' => $faker->text(500),
            'price' => 100,
            'category' => 1,
            'account' => 1
        );

        $url = '/api/v1/services';
        $response = $client->post($url, [
            'json' => $data
        ]);

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

    }

    public function testServiceCreateBadCategory()
    {

        try {

            $client = $this->authorize($this->getApiClient());

            $faker = \Faker\Factory::create('en_EN');

            $data = array(
                'name' => $faker->name,
                'body' => $faker->text(500),
                'price' => 100,
                'category' => -1,
                'account' => 1
            );

            $url = '/api/v1/services';

            $client->post($url, [
                'json' => $data
            ]);

        }
        catch(RequestException $e) {
            $this->assertEquals(Response::HTTP_BAD_REQUEST, $e->getResponse()->getStatusCode());
            $this->assertEquals(1, preg_match('/Please enter a valid Service Category/', $e->getMessage()));
        }

    }

    public function testServiceCreateBadAccount()
    {

        try {

            $client = $this->authorize($this->getApiClient());

            $faker = \Faker\Factory::create('en_EN');

            $data = array(
                'name' => $faker->name,
                'body' => $faker->text(500),
                'price' => 100,
                'category' => 1,
                'account' => -1
            );

            $url = '/api/v1/services';

            $client->post($url, [
                'json' => $data
            ]);

        }
        catch(RequestException $e) {
            $this->assertEquals(Response::HTTP_BAD_REQUEST, $e->getResponse()->getStatusCode());
            $this->assertEquals(1, preg_match('/Please enter a valid Account/', $e->getMessage()));
        }

    }

    public function testServiceCreateBadPrice()
    {

        try {

            $client = $this->authorize($this->getApiClient());

            $faker = \Faker\Factory::create('en_EN');

            $data = array(
                'name' => $faker->name,
                'body' => $faker->text(500),
                'price' => 0,
                'category' => 1,
                'account' => 1
            );

            $url = '/api/v1/services';

            $client->post($url, [
                'json' => $data
            ]);

        }
        catch(RequestException $e) {
            $this->assertEquals(Response::HTTP_BAD_REQUEST, $e->getResponse()->getStatusCode());
            $this->assertEquals(1, preg_match('/Price must be greater than 0/', $e->getMessage()));
        }

    }

}