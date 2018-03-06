<?php namespace Tests\Integration\ApiBundle\Controller\Entity;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Response;
use Tests\TavroApiTest;

class ShareholderTest extends TavroApiTest
{

    public function testShareholderRoute()
    {
        $client = $this->authorize($this->getApiClient());

        $url = '/api/v1/shareholders';

        $response = $client->get($url);

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    public function testShareholderCreate()
    {

        $client = $this->authorize($this->getApiClient());
        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'person' => 1,
            'body' => $faker->text(500),
        );

        $url = '/api/v1/shareholders';

        $response = $client->post($url, [
            'json' => $data
        ]);

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

    }

}