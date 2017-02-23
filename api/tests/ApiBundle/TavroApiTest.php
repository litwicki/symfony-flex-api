<?php namespace Tavro\Tests\ApiBundle;

use GuzzleHttp\Client;

class TavroApiTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Get a "naked" Api Client.
     *
     * @param string $base_uri
     *
     * @return \GuzzleHttp\Client
     */
    public function getApiClient($base_uri = 'http://api.tavro.dev')
    {
        return new Client([
            'base_uri' => $base_uri,
            'request.options' => array(
                'exceptions' => FALSE,
            )
        ]);
    }

    /**
     * @param \GuzzleHttp\Client $client
     * @param array $options
     *
     * @return \GuzzleHttp\Client
     * @throws \Exception
     */
    public function authorize(Client $client, array $options = array())
    {
        $client = $this->getApiClient();

        $username = isset($options['username']) ? $options['username'] : 'tavrobot';
        $password = isset($options['password']) ? $options['password'] : 'Password1!';
        $https = isset($options['https']) ? $options['https'] : false;

        $data = array(
            'username' => $username,
            'password' => $password,
        );

        $url = '/api/v1/auth';

        $response = $client->post($url, [
            'verify' => $https,
            'form_params' => $data
        ]);

        $code = $response->getStatusCode();

        $body = json_decode($response->getBody(), true);

        if($code === 200 && isset($body['token'])) {

            $uri = $client->getConfig('base_uri');

            return new Client([
                'verify' => $https,
                'base_uri' => $uri,
                'request.options' => [
                    'exceptions' => FALSE
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => sprintf('Bearer %s', $body['token'])
                ],
            ]);

        }

        throw new \Exception(
            sprintf('Unable to authorize Api access with username: `%s`, password: `%s`, https: `%s`', $username, $password, $https)
        );
    }
}