<?php namespace Tests\CoreBundle\Controller;

use Guzzle\Http\Client;

class RolesTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Create a client with a default Authorization header.
     *
     * @param string $username
     * @param string $password
     *
     * @return \Guzzle\Http\Client
     */

    protected function createAuthenticatedClient($username = 'tavrobot', $password = 'Password1!')
    {
        $client = new Client();
        $response = $client->post('/api/v1/login_check', array(
            '_username' => $username,
            '_password' => $password,
        ));

        $data = json_decode($response, true);

        $client = new Client('http://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
                'headers' => array(
                    'Bearer' => $data['token']
                )
            )
        ));

        return $client;
    }

    /**
     * test getPagesAction
     */
    public function testLogin()
    {
        $client = $this->createAuthenticatedClient();
        $client->get('/api/v1/login_check');
    }

}