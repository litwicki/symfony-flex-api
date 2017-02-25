<?php namespace Tests\ApiBundle\Controller;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Exception\RequestException;
use Tests\ApiBundle\TavroApiTest;

class FundingRoundTest extends TavroApiTest
{

    public function testFundingRoundRoute()
    {
        $client = $this->authorize($this->getApiClient());

        $url = '/api/v1/funding';

        $response = $client->get($url);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testFundingRoundCreate()
    {
        $client = $this->authorize($this->getApiClient());

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'body' => $faker->text(500),
            'type' => 'funding_round_test',
            'account' => 1
        );

        $url = '/api/v1/funding';

        $response = $client->post($url, [
            'json' => $data,
        ]);

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

    }

    public function testFundingRoundCreateBadAccount()
    {
        try {

            $client = $this->authorize($this->getApiClient());

            $faker = \Faker\Factory::create('en_EN');

            $data = array(
                'body' => $faker->text(500),
                'type' => 'funding_round_test',
                'account' => -1
            );

            $url = '/api/v1/funding';

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