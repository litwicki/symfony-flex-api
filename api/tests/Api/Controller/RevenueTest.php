<?php namespace Tavro\Tests\Api\Controller;

use Guzzle\Http\Client;
use Tavro\Bundle\CoreBundle\Testing\TavroTest;

class RevenueTest extends TavroTest
{

    public function testRevenueRoute()
    {
        $client = new Client('http://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $token = $this->authorize();

        $url = 'http://api.tavro.dev/api/v1/revenues';

        $request = $client->get($url);
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testRevenueCreateRevenueWithServices()
    {

        $token = $this->authorize();

        $data = array(
            'type' => 'service',
            'category' => 1,
            'user' => 1,
            'services' => array(1,2,3),
            'account' => 1
        );

        $url = 'http://api.tavro.dev/api/v1/revenues';

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

    public function testRevenueCreateRevenueWithProducts()
    {

        $token = $this->authorize();

        $data = array(
            'type' => 'sale',
            'category' => 1,
            'user' => 1,
            'products' => array(1,2,3),
            'account' => 1
        );

        $url = 'http://api.tavro.dev/api/v1/revenues';

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