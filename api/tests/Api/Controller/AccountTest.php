<?php namespace Tests\Api\Controller;

use Guzzle\Http\Client;
use Tavro\Bundle\CoreBundle\Testing\TavroTest;

class AccountTest extends TavroTest
{

    public function testAccountRoute()
    {
        $client = new Client('http://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $token = $this->authorize();

        $url = 'http://api.tavro.dev/api/v1/accounts';

        $request = $client->get($url);
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testAccountCreate()
    {

        $token = $this->authorize();

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'name' => 'Account Name',
            'body' => 'Account body.....',
            'user' => 1
        );

        $url = 'http://api.tavro.dev/api/v1/accounts';

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
        var_dump($body);
        die(__METHOD__);
        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testAccountCreateBadOrganization()
    {

        $token = $this->authorize();

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'name' => 'Account Name',
            'body' => 'Account body description.',
            'user' => 1,
            'organization' => -1
        );

        $url = 'http://api.tavro.dev/api/v1/accounts';

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

    public function testAccountCreateBadUser()
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
            'name' => 'Account Name',
            'body' => 'Account body description.',
            'user' => -1,
            'organization' => 1
        );

        $url = 'http://api.tavro.dev/api/v1/accounts';

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