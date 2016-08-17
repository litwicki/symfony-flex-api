<?php namespace Tavro\Bundle\CoreBundle\Testing;

use Guzzle\Http\Client;

class TavroTest extends \PHPUnit_Framework_TestCase
{
    public function authorize()
    {
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
        $token = $body['token'];

        return $token;
    }
}