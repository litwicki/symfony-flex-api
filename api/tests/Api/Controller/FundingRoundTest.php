<?php namespace Tavro\Tests\Api\Controller;

use GuzzleHttp\Client;;
use Tavro\Bundle\CoreBundle\Testing\TavroTest;

class FundingRoundTest extends TavroTest
{

    public function testFundingRoundRoute()
    {
        $client = new Client('https://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $token = $this->authorize();

        $url = 'https://api.tavro.dev/api/v1/funding';

        $request = $client->get($url, null, ['verify' => false]);
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testFundingRoundCreate()
    {
        // create our http client (Guzzle)
        $client = new Client('https://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $token = $this->authorize();

        $data = array(
            'body' => 'FundingRound body description.',
            'type' => 'funding_round_test',
            'account' => 1
        );

        $url = 'https://api.tavro.dev/api/v1/funding';

        $client = new Client($url, array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $request = $client->post($url, null, json_encode($data), ['verify' => false]);
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());

    }

}