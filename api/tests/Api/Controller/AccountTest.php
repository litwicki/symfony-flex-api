<?php namespace Tavro\Tests\Api\Controller;

use GuzzleHttp\Client;;
use Tavro\Bundle\CoreBundle\Testing\TavroTest;

class AccountTest extends TavroTest
{

    public function testAccountRoute()
    {
        $client = new Client('https://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $token = $this->authorize();

        $url = 'https://api.tavro.dev/api/v1/accounts';

        $request = $client->get($url, null, ['verify' => false]);
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testAccountCreate()
    {

        $token = $this->authorize();

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'name' => $faker->company,
            'body' => $faker->text(rand(100,1000)),
            'user' => 1
        );

        $url = 'https://api.tavro.dev/api/v1/accounts';

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

    public function testAccountCreateBadUser()
    {
        $token = $this->authorize();

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'name' => $faker->company,
            'body' => $faker->text(rand(100,1000)),
            'user' => -1,
        );

        $url = 'https://api.tavro.dev/api/v1/accounts';

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

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(1, preg_match('/This value is not valid./', $body['message']));

    }

}