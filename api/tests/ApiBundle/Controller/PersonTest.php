<?php namespace Tests\ApiBundle\Controller;

use GuzzleHttp\Client;;
use Tests\ApiBundle\TavroApiTest;

class PersonTest extends TavroApiTest
{

    public function testPersonRoute()
    {
        $client = new Client('/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $client = $this->authorize($this->getApiClient());;

        $url = '/api/v1/people';

        $request = $client->get($url, null, ['verify' => false]);
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testPersonCreate()
    {
        $client = $this->authorize($this->getApiClient());;

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

        $url = '/api/v1/people';

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
        $client = $this->authorize($this->getApiClient());;

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

        $url = '/api/v1/people';

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