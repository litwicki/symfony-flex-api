<?php namespace Tests\Api\Controller;

use Guzzle\Http\Client;
use Tavro\Bundle\CoreBundle\Testing\TavroTest;

class CustomerTest extends TavroTest
{

    public function testCustomerRoute()
    {
        $client = new Client('http://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $token = $this->authorize();

        $url = 'http://api.tavro.dev/api/v1/customers';

        $request = $client->get($url);
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testCustomerCreate()
    {
        // create our http client (Guzzle)
        $client = new Client('http://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $token = $this->authorize();

        $data = array(
            'job_title' => 'Wizard',
            'person' => 1,
            'account' => 1
        );

        $url = 'http://api.tavro.dev/api/v1/customers';

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

}