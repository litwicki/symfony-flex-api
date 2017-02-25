<?php namespace Tests\ApiBundle\Controller;

use GuzzleHttp\Client;;
use Tests\ApiBundle\TavroApiTest;

class ContactTest extends TavroApiTest
{

    public function testContactRoute()
    {
        $client = new Client('/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $client = $this->authorize($this->getApiClient());;

        $url = '/api/v1/contacts';

        $request = $client->get($url, null, ['verify' => false]);
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testContactCreate()
    {

        $client = $this->authorize($this->getApiClient());;

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'job_title' => $faker->jobTitle,
            'email' => $faker->email,
            'phone' => '555-867-5309',
            'person' => 1,
            'organization' => 1
        );

        $url = '/api/v1/contacts';

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

    public function testContactCreateWithUser()
    {

        $client = $this->authorize($this->getApiClient());;

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'job_title' => $faker->jobTitle,
            'email' => $faker->email,
            'phone' => '555-867-5309',
            'person' => 1,
            'user' => 1,
            'organization' => 1
        );

        $url = '/api/v1/contacts';

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

    public function testContactCreateBadOrganization()
    {

        $client = $this->authorize($this->getApiClient());;

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'job_title' => $faker->jobTitle,
            'email' => $faker->email,
            'phone' => '555-867-5309',
            'person' => 1,
            'user' => 1,
            'organization' => -1
        );

        $url = '/api/v1/contacts';

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
        $this->assertEquals(1, preg_match('/Please enter a valid Organization/', $body['message']));

    }

    public function testContactCreateBadUser()
    {
        // create our http client (Guzzle)
        $client = new Client('/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $client = $this->authorize($this->getApiClient());;

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'name' => 'Contact Name',
            'body' => 'Contact body description.',
            'user' => -1,
            'organization' => 1
        );

        $url = '/api/v1/contacts';

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