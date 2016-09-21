<?php namespace Tavro\Bundle\CoreBundle\Testing;

use Guzzle\Http\Client;

class TavroTest extends \PHPUnit_Framework_TestCase
{
    public function authorize($username = 'tavrobot', $password = 'Password1!')
    {
        $client = new Client('http://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => FALSE,
            )
        ));

        $data = array(
            'username' => $username,
            'password' => $password
        );

        $request = $client->post('http://api.tavro.dev/api/v1/auth', null, $data);
        $response = $request->send();

        $json = $response->getBody(TRUE);

        $body = json_decode($json, TRUE);

        if(isset($body['token'])) {
            return $body['token'];
        }

        return FALSE;
    }
}