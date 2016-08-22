<?php namespace Tests\Api\Controller;

use Guzzle\Http\Client;
use Tavro\Bundle\CoreBundle\Testing\TavroTest;
use Rhumsaa\Uuid\Uuid;

class UserTest extends TavroTest
{

    public function testUserRoute()
    {
        $client = new Client('http://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $token = $this->authorize();

        $url = 'http://api.tavro.dev/api/v1/users';

        $request = $client->get($url);
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testUserCreate()
    {

        $token = $this->authorize();

        $faker = \Faker\Factory::create('en_EN');

        $guid = Uuid::uuid4();

        $data = array(
            'person' => 1,
            'salt' => md5(time()),
            'signature' => 'signature',
            'username' => md5(time()),
            'password' => 'Password1!',
            'api_enabled' => true,
            'api_key' => Uuid::uuid5(Uuid::NAMESPACE_DNS, $guid),
            'api_password' => bin2hex(openssl_random_pseudo_bytes(12)),
            'status' => 1,
            'guid' => Uuid::uuid5(Uuid::NAMESPACE_DNS, $guid),
            'user_ip' => '192.168.50.1',
            'user_agent' => 'Mozilla blahbidy blah',
            'last_online_date' => $faker->dateTimeThisMonth->format('Y-m-d h:i:s'),
            'body' => $faker->text(500),

        );

        $url = 'http://api.tavro.dev/api/v1/users';

        $client = new Client($url, array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $request = $client->post($url, null, json_encode($data));
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);
        var_dump($body);die();

        $this->assertEquals(200, $response->getStatusCode());

    }

}