<?php namespace Tests\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Guzzle\Http\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class LoginTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Create a client with a default Authorization header.
     *
     * @param string $username
     * @param string $password
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */

    protected function createAuthenticatedClient($username = 'tavrobot', $password = 'Password1!')
    {
        $client = new Client();
        $response = $client->post('/api/login_check', array(
            '_username' => $username,
            '_password' => $password,
        ));

        $data = json_decode($response, true);

        $client = new Client('http://api.tavro.dev', array(
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
    public function testGetPages()
    {
        $client = $this->createAuthenticatedClient();
        //$client->request('GET', '/api/pages');
    }

}