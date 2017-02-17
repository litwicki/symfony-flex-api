<?php namespace Tests\ApiBundle;

use GuzzleHttp\Client;

class TavroApiTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Client
     */
    public function getApiClient()
    {
        return new Client([
            'base_uri' => 'http://api.tavro.dev',
            'request.options' => array(
                'exceptions' => FALSE,
            )
        ]);
    }

    /**
     * Authorize an Api request and fetch a JWT token.
     *
     * @param string $username
     * @param string $password
     * @param bool $https
     *
     * @return mixed
     */
    public function authorize($username = 'tavrobot', $password = 'Password1!', $https = false)
    {
        $client = $this->getApiClient();

        $data = array(
            'username' => $username,
            'password' => $password
        );

        $response = $client->request('POST', '/api/v1/auth', [
            'verify' => $https,
            'form_params' => $data
        ]);

        $code = $response->getStatusCode();

        $body = json_decode($response->getBody(), true);

        if($code === 200 && isset($body['token'])) {
            return $body['token'];
        }

        throw new \Exception(
            sprintf('Unable to authorize Api access with username: `%s`, password: `%s`, https: `%s`', $username, $password, $https)
        );
    }
}