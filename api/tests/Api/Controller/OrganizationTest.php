<?php namespace Tests\Api\Controller;

use Guzzle\Http\Client;
use Tavro\Bundle\CoreBundle\Testing\TavroTest;

class OrganizationTest extends TavroTest
{

    public function testOrganizationRoute()
    {
        $client = new Client('http://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $token = $this->authorize();

        $url = 'http://api.tavro.dev/api/v1/organizations';

        $request = $client->get($url);
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testOrganizationCreate()
    {

        $token = $this->authorize();

        $data = array(
            'title' => 'Organization Name',
            'body' => 'Product body..',
            'owner' => 1,
        );

        $url = 'http://api.tavro.dev/api/v1/organizations';

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

    public function testOrganizationCreateWithNoPermission()
    {

        $token = $this->authorize('fembot', 'Password1!');

        $this->assertTrue(!empty($token));

        $data = array(
            'title' => 'Organization Name',
            'body' => 'Product body..',
            'customer_label' => 'Customer',
            'owner' => 1,
        );

        $url = 'http://api.tavro.dev/api/v1/organizations';

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

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals(1, preg_match('/You are not authorized/', $body['message']));

    }

}