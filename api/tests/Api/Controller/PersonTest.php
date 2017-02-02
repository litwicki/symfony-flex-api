<?php namespace Tavro\Tests\Api\Controller;

use Guzzle\Http\Client;
use Tavro\Bundle\CoreBundle\Testing\TavroTest;

class PersonTest extends TavroTest
{

    public function testPersonRoute()
    {
        $client = new Client('https://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $token = $this->authorize();

        $url = 'https://api.tavro.dev/api/v1/people';

        $request = $client->get($url, null, ['verify' => false]);
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testPersonCreate()
    {
        $token = $this->authorize();

        $faker = \Faker\Factory::create('en_EN');
        $genders = array('male', 'female');

        $gender = $genders[rand(0,1)];

        $data = [
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'body' => $faker->text(500),
            'email' => $faker->email,
            'gender' => $gender,
            'phone' => '555-867-5309',
            'address' => $faker->address,
            'city' => $faker->city,
            'state' => $faker->state,
            'zip' => $faker->postcode,
        ];

        $url = 'https://api.tavro.dev/api/v1/people';

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

    public function testPersonCreateBadGender()
    {
        $token = $this->authorize();

        $faker = \Faker\Factory::create('en_EN');

        $data = [
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'body' => $faker->text(500),
            'email' => $faker->email,
            'gender' => 'gremlin',
            'phone' => '555-867-5309',
            'address' => $faker->address,
            'city' => $faker->city,
            'state' => $faker->state,
            'zip' => $faker->postcode,
        ];

        $url = 'https://api.tavro.dev/api/v1/people';

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

        $this->assertEquals(1, preg_match('/valid gender/', $body['message']));

    }

}