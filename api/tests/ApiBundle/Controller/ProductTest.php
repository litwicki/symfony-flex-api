<?php namespace Tests\ApiBundle\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Response;
use Tests\ApiBundle\TavroApiTest;

class ProductTest extends TavroApiTest
{

    public function testProductRoute()
    {
        $client = $this->authorize($this->getApiClient());

        $url = '/api/v1/products';

        $response = $client->get($url);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    public function testProductCreate()
    {
        $faker = \Faker\Factory::create('en_EN');

        $client = $this->authorize($this->getApiClient());

        $data = array(
            'name' => $faker->text(rand(10,100)),
            'body' => $faker->text(rand(100,1000)),
            'price' => 100,
            'cost' => 75,
            'category' => 1,
            'account' => 1
        );

        $url = '/api/v1/products';

        $response = $client->post($url, [
            'json' => $data
        ]);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

    }

    public function testProductCreateBadAccount()
    {
        try {
            $faker = \Faker\Factory::create('en_EN');

            $client = $this->authorize($this->getApiClient());

            $data = array(
                'name' => $faker->text(rand(10,100)),
                'body' => $faker->text(rand(100,1000)),
                'price' => 100,
                'cost' => 75,
                'category' => 1,
                'account' => -1
            );

            $url = '/api/v1/products';

            $client->post($url, [
                'json' => $data
            ]);
        }
        catch(RequestException $e) {
            $this->assertEquals(Response::HTTP_BAD_REQUEST, $e->getResponse()->getStatusCode());
            $this->assertEquals(1, preg_match('/Please enter a valid Account/', $e->getMessage()));
        }

    }

    public function testProductCreateBadCategory()
    {
        try {
            $faker = \Faker\Factory::create('en_EN');

            $client = $this->authorize($this->getApiClient());

            $data = array(
                'name' => $faker->text(rand(10,100)),
                'body' => $faker->text(rand(100,1000)),
                'price' => 100,
                'cost' => 75,
                'category' => -1,
                'account' => 1
            );

            $url = '/api/v1/products';

            $client->post($url, [
                'json' => $data
            ]);
        }
        catch(RequestException $e) {
            $this->assertEquals(Response::HTTP_BAD_REQUEST, $e->getResponse()->getStatusCode());
            $this->assertEquals(1, preg_match('/Please enter a valid Product Category/', $e->getMessage()));
        }

    }

}