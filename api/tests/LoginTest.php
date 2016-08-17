<?php namespace Tests\CoreBundle;

use Guzzle\Http\Client;

class LoginTest extends \PHPUnit_Framework_TestCase
{

    public function testBadLogin()
    {
        // create our http client (Guzzle)
        $client = new Client('http://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $data = array(
            'username' => 'user',
            'password' => 'password'
        );

        $request = $client->post('http://api.tavro.dev/api/v1/auth', null, $data);
        $response = $request->send();

        //using an invalid password/username should yield a 404 Not Found
        $this->assertEquals(404, $response->getStatusCode());

    }

    public function testGoodLogin()
    {
        // create our http client (Guzzle)
        $client = new Client('http://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $data = array(
            'username' => 'tavrobot',
            'password' => 'Password1!'
        );

        $request = $client->post('http://api.tavro.dev/api/v1/auth', null, $data);
        $response = $request->send();

        $json = $response->getBody(true);

        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(isset($body['token']));

    }

    public function testNotLoggedIn()
    {
        // create our http client (Guzzle)
        $client = new Client('http://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $request = $client->get('http://api.tavro.dev/api/v1/users');
        $response = $request->send();

        $json = $response->getBody(true);

        $this->assertEquals(401, $response->getStatusCode());
    }

}