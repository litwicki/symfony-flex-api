<?php namespace Tests\ApiBundle;

use GuzzleHttp\Client;

class TavroApiTest extends \PHPUnit_Framework_TestCase
{
    public function authorize($username = 'tavrobot', $password = 'Password1!')
    {
        $client = new Client(array(
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