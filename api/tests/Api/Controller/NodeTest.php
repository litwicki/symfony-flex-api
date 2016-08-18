<?php namespace Tests\Api\Controller;

use Guzzle\Http\Client;
use Tavro\Bundle\CoreBundle\Testing\TavroTest;

class NodeTest extends TavroTest
{

    public function testNodeRoute()
    {
        $client = new Client('http://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $token = $this->authorize();

        $url = 'http://api.tavro.dev/api/v1/nodes';

        $request = $client->get($url);
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
            'organization' => 1
        );

        $url = 'http://api.tavro.dev/api/v1/nodes';

        $client = new Client($url, array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $request = $client->post($url, null, json_encode($data));
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testNodeCreateBadOrganization()
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
            'organization' => -1
        );

        $url = 'http://api.tavro.dev/api/v1/nodes';

        $client = new Client($url, array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $request = $client->post($url, null, json_encode($data));
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(1, preg_match('/Please enter a valid Organization/', $body['message']));

    }

    public function testNodeCreateBadUser()
    {
        // create our http client (Guzzle)
        $client = new Client('http://api.tavro.dev/api/v1', array(
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
            'organization' => 1
        );

        $url = 'http://api.tavro.dev/api/v1/nodes';

        $client = new Client($url, array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $request = $client->post($url, null, json_encode($data));
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(1, preg_match('/Please enter a valid User/', $body['message']));

    }

}