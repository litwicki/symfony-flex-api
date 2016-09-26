<?php namespace Tavro\Tests\Api\Controller;

use Guzzle\Http\Client;

class LoginTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @group ApiAuth
     */
    public function testTokenAuthenticateAction()
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

    /**
     * @group ApiAuth
     */
    public function testForgotAction()
    {
        // create our http client (Guzzle)
        $client = new Client('http://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $request = $client->post('http://api.tavro.dev/api/v1/auth/forgot', null, json_encode([
            'email' => 'dev@zoadilack.com',
        ]));

        $response = $request->send();

        $json = $response->getBody(true);

        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(isset($body['message']));
    }

    /**
     * @group ApiAuth
     */
    public function testResetAction()
    {
        // create our http client (Guzzle)
        $client = new Client('http://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $request = $client->post('http://api.tavro.dev/api/v1/auth/reset', null, json_encode([
            'email' => 'dev@zoadilack.com',
        ]));

        $response = $request->send();

        $json = $response->getBody(true);

        $body = json_decode($json, true);
        die(var_dump($body));
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(isset($body['message']));
    }

    /**
     * @group ApiAuth
     */
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

    /**
     * @group ApiAuth
     */
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
        $body = json_decode($json, true);

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals(1, preg_match('/You must be authorized to access this resource/', $body['message']));
    }

}