<?php namespace Tests\Integration\ApiBundle\Controller\AccountEntity;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Response;
use Tests\TavroApiTest;

class NodeTest extends TavroApiTest
{

    public function testNodeRoute()
    {
        $client = $this->authorize($this->getApiClient());

        $url = '/api/v1/accounts/1/nodes';

        $response = $client->get($url);

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    public function testNodeCreate()
    {

        $client = $this->authorize($this->getApiClient());;

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'title' => $faker->text(200),
            'body' => $faker->text(500),
            'type' => 'node',
            'views' => 1,
            'display_date' => $faker->dateTimeThisMonth->format('Y-m-d h:i:s'),
            'user' => 1,
            'account' => 1
        );

        $url = '/api/v1/accounts/1/nodes';

        $response = $client->post($url, [
            'json' => $data
        ]);

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

    }

    public function testNodeCreateBadAccount()
    {

        try {

            $client = $this->authorize($this->getApiClient());

            $faker = \Faker\Factory::create('en_EN');

            $data = array(
                'title' => 'Node Name',
                'body' => 'Node body description.',
                'type' => 'node',
                'views' => 1,
                'display_date' => $faker->dateTimeThisMonth->format('Y-m-d h:i:s'),
                'user' => 1,
                'account' => -1
            );

            $url = '/api/v1/accounts/-1/nodes';

            $client->post($url, [
                'json' => $data
            ]);

        }
        catch(RequestException $e) {
            $this->assertEquals(Response::HTTP_BAD_REQUEST, $e->getResponse()->getStatusCode());
            $this->assertEquals(1, preg_match('/Account object not found/', $e->getMessage()));
        }

    }

    public function testNodeCreateBadUser()
    {
        try {

            $client = $this->authorize($this->getApiClient());

            $faker = \Faker\Factory::create('en_EN');

            $data = array(
                'title' => $faker->text(200),
                'body' => $faker->text(500),
                'type' => 'node',
                'views' => 1,
                'display_date' => $faker->dateTimeThisMonth->format('Y-m-d h:i:s'),
                'user' => -1,
                'account' => 1
            );

            $url = '/api/v1/accounts/1/nodes';

            $client->post($url, [
                'json' => $data
            ]);

        }
        catch(RequestException $e) {
            $this->assertEquals(Response::HTTP_BAD_REQUEST, $e->getResponse()->getStatusCode());
            $this->assertEquals(1, preg_match('/Please enter a valid User/', $e->getMessage()));
        }

    }

}