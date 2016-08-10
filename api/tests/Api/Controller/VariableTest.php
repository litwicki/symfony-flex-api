<?php namespace Tests\Api\Controller;

use Guzzle\Http\Client;

class VariableTest extends \PHPUnit_Framework_TestCase
{

    public function testVariableRoute()
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

        $url = 'http://api.tavro.dev/api/v1/variables';

        $request = $client->get($url);
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());

    }

}