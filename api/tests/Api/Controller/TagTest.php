<?php namespace Tests\Api\Controller;

use Guzzle\Http\Client;

class TagTest extends \PHPUnit_Framework_TestCase
{

    public function testTagRoute()
    {
        $client = new Client('http://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $data = array(
            '_username' => 'tavrobot',
            '_password' => 'Password1!'
        );

        $request = $client->post('http://api.tavro.dev/api/v1/login_check', null, $data);
        $response = $request->send();

        $json = $response->getBody(true);

        $body = json_decode($json, true);
        $token = $body['token'];

        $url = 'http://api.tavro.dev/api/v1/tags';

        $request = $client->get($url);
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testTagCreate()
    {
        // create our http client (Guzzle)
        $client = new Client('http://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $data = array(
            '_username' => 'tavrobot',
            '_password' => 'Password1!'
        );

        $request = $client->post('http://api.tavro.dev/api/v1/login_check', null, $data);
        $response = $request->send();

        $json = $response->getBody(true);

        $body = json_decode($json, true);
        $token = $body['token'];

        $data = array(
            'title' => 'Tag Name',
            'body' => 'Tag body description.',
            'price' => 100,
            'cost' => 75,
            'status' => 1,
            'category' => 1,
            'organization' => 1
        );

        $url = 'http://api.tavro.dev/api/v1/Tags';

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