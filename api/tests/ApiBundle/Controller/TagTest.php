<?php namespace Tests\ApiBundle\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Response;
use Tests\ApiBundle\TavroApiTest;

class TagTest extends TavroApiTest
{

    public function testTagRoute()
    {
        $client = $this->authorize($this->getApiClient());

        $url = '/api/v1/tags';

        $response = $client->get($url);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    public function testTagCreate()
    {
        $client = $this->authorize($this->getApiClient());

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'tag' => $faker->text(20)
        );

        $url = '/api/v1/tags';

        $response = $client->post($url, [
            'json' => $data
        ]);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

    }

}