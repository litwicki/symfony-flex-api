<?php namespace Tests\ApiBundle\Controller;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;
use Tests\ApiBundle\TavroApiTest;

class AuthControllerTest extends TavroApiTest
{

    /**
     * @group ApiAuth
     */
    public function testTokenAuthenticateAction()
    {
        $this->authorize($this->getApiClient());
    }

    /**
     * @group ApiAuth
     */
    public function testForgotAction()
    {

        $client = new Client([
            'verify' => false,
            'base_uri' => 'http://api.tavro.dev',
            'request.options' => [
                'exceptions' => false
            ],
            'headers' => [
                'Content-Type' => 'application/json'
            ],
        ]);

        $data = array(
            'email' => 'dev@zoadilack.com'
        );

        $url = '/api/v1/auth/forgot';

        $response = $client->post($url, [
            'json' => $data
        ]);

        $json = $response->getBody();

        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(isset($body['message']));
    }

    /**
     * @group ApiAuth
     */
    public function testResetAction()
    {
        $client = new Client('http://api.tavro.dev', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $data = [
            'email' => 'dev@zoadilack.com',
            'new_password' => 'Password1!',
            'new_password_confirm' => 'Password1!'
        ];

        $response = $client->post('/api/v1/auth/reset', [
            'verify' => false,
            'json' => $data
        ]);

        $json = $response->getBody();

        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(isset($body['message']));
    }

    /**
     * @group ApiAuth
     */
    public function testBadLogin()
    {
        $client = new Client('http://api.tavro.dev', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $data = array(
            'username' => 'user',
            'password' => 'password'
        );

        $response = $client->post('/api/v1/auth', [
            'verify' => false,
            'json' => $data
        ]);

        //using an invalid password/username should yield a 404 Not Found
        $this->assertEquals(404, $response->getStatusCode());

    }

    /**
     * @group ApiAuth
     */
    public function testNotLoggedIn()
    {
        $client = new Client('http://api.tavro.dev', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $response = $client->get('/api/v1/users');

        $json = $response->getBody();
        $body = json_decode($json, true);

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals(1, preg_match('/You must be authorized to access this resource/', $body['message']));
    }

}