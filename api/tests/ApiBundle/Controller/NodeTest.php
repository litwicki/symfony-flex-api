<?php namespace Tavro\Tests\ApiBundle\Controller;

use GuzzleHttp\Client;;
use Tavro\Tests\ApiBundle\TavroApiTest;

class NodeTest extends TavroApiTest
{

    public function testNodeRoute()
    {
        $client = new Client('/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $token = $this->authorize();

        $url = '/api/v1/nodes';

        $request = $client->get($url, null, ['verify' => false]);
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testNodeCreate()
    {

        $token = $this->authorize();

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'title' => 'Node Name',
            'body' => 'Node body description.',
            'type' => 'node',
            'views' => 1,
            'display_date' => $faker->dateTimeThisMonth->format('Y-m-d h:i:s'),
            'user' => 1,
            'account' => 1
        );

        $url = '/api/v1/nodes';

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

    public function testNodeCreateBadAccount()
    {

        $token = $this->authorize();

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

        $url = '/api/v1/nodes';

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

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(1, preg_match('/Please enter a valid Account/', $body['message']));

    }

    public function testNodeCreateBadUser()
    {
        // create our http client (Guzzle)
        $client = new Client('/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $token = $this->authorize();

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'title' => 'Node Name',
            'body' => 'Node body description.',
            'type' => 'node',
            'views' => 1,
            'display_date' => $faker->dateTimeThisCentury,
            'user' => -1,
            'account' => 1
        );

        $url = '/api/v1/nodes';

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

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(1, preg_match('/Please enter a valid User/', $body['message']));

    }

}