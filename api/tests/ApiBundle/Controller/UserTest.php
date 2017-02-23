<?php namespace Tavro\Tests\ApiBundle\Controller;

use GuzzleHttp\Client;;
use Tavro\Tests\ApiBundle\TavroApiTest;
use Rhumsaa\Uuid\Uuid;

class UserTest extends TavroApiTest
{

    public function testUserRoute()
    {
        $client = new Client('/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $token = $this->authorize();

        $url = '/api/v1/users';

        $request = $client->get($url, null, ['verify' => false]);
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

        $email = $faker->safeEmail;

        $data = array(
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => $email,
            'username' => md5(time()),
            'signature' => $faker->text(100),
            'password' => 'Password1!'
        );

        $url = '/api/v1/signup';

        $client = new Client($url, array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $request = $client->post($url, null, json_encode($data), ['verify' => false]);
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());

    }

}