<?php namespace Tests\ApiBundle\Controller;

use GuzzleHttp\Client;;
use Tests\ApiBundle\TavroApiTest;

class RevenueTest extends TavroApiTest
{

    public function testRevenueRoute()
    {
        $client = new Client('/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $client = $this->authorize($this->getApiClient());;

        $url = '/api/v1/revenues';

        $request = $client->get($url, null, ['verify' => false]);
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testRevenueCreateRevenueWithServices()
    {

        $client = $this->authorize($this->getApiClient());;

        $data = array(
            'type' => 'service',
            'category' => 1,
            'user' => 1,
            'services' => array(1,2,3),
            'account' => 1
        );

        $url = '/api/v1/revenues';

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

    public function testRevenueCreateRevenueWithProducts()
    {

        $client = $this->authorize($this->getApiClient());;

        $data = array(
            'type' => 'sale',
            'category' => 1,
            'user' => 1,
            'products' => array(1,2,3),
            'account' => 1
        );

        $url = '/api/v1/revenues';

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