<?php namespace Tests\Api\Controller;

use Guzzle\Http\Client;
use Tavro\Bundle\CoreBundle\Testing\TavroTest;

class ContactTest extends TavroTest
{

    public function testContactRoute()
    {
        $client = new Client('http://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $token = $this->authorize();

        $url = 'http://api.tavro.dev/api/v1/contacts';

        $request = $client->get($url);
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testContactCreate()
    {

        $token = $this->authorize();

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'name' => 'Contact Name',
            'body' => 'Contact body description.',
            'user' => 1,
            'organization' => 1
        );

        $url = 'http://api.tavro.dev/api/v1/contacts';

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

    public function testContactCreateBadOrganization()
    {

        $token = $this->authorize();

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'name' => 'Contact Name',
            'body' => 'Contact body description.',
            'user' => 1,
            'organization' => -1
        );

        $url = 'http://api.tavro.dev/api/v1/contacts';

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

    public function testContactCreateBadUser()
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
            'name' => 'Contact Name',
            'body' => 'Contact body description.',
            'user' => -1,
            'organization' => 1
        );

        $url = 'http://api.tavro.dev/api/v1/contacts';

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