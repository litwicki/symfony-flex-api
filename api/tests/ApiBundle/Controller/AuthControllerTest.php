<?php namespace Tavro\Tests\ApiBundle\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Response;
use Tavro\Tests\ApiBundle\TavroApiTest;

class AuthControllerTest extends TavroApiTest
{

    public function getAuthClient()
    {
        return new Client([
            'verify' => false,
            'base_uri' => 'http://api.tavro.dev',
            'request.options' => [
                'exceptions' => false
            ]
        ]);
    }

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

        $client = $this->getAuthClient();

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
        $client = $this->getAuthClient();

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
        try {
            $client = $this->getAuthClient();

            $data = array(
                'username' => 'user',
                'password' => 'password'
            );

            $response = $client->post('/api/v1/auth', [
                'verify' => false,
                'form_params' => $data
            ]);

        }
        catch(RequestException $e) {
            //using an invalid password/username should yield a 404 Not Found
            $this->assertEquals(Response::HTTP_NOT_FOUND, $e->getResponse()->getStatusCode());
        }

    }

    /**
     * @group ApiAuth
     */
    public function testNotLoggedIn()
    {
        try {
            $client = $this->getAuthClient();
            $response = $client->get('/api/v1/users');
        }
        catch(RequestException $e) {
            $this->assertEquals(Response::HTTP_UNAUTHORIZED, $e->getResponse()->getStatusCode());
        }
    }

}