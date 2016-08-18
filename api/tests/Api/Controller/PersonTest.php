<?php namespace Tests\Api\Controller;

use Guzzle\Http\Client;
use Tavro\Bundle\CoreBundle\Testing\TavroTest;

class PersonTest extends TavroTest
{

    public function testPersonRoute()
    {
        $client = new Client('http://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $token = $this->authorize();

        $url = 'http://api.tavro.dev/api/v1/people';

        $request = $client->get($url);
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
            'title' => 'blah',
            'suffix' => $faker->suffix,
            'email' => $faker->email,
            'gender' => $gender,
            'birthday' => $faker->dateTimeThisCentury
        ];

        $url = 'http://api.tavro.dev/api/v1/people';

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

    public function testPersonCreateBadGender()
    {
        $token = $this->authorize();

        $faker = \Faker\Factory::create('en_EN');

        $data = [
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'title' => 'blah',
            'suffix' => $faker->suffix,
            'email' => $faker->email,
            'gender' => 'gremlin',
            'birthday' => $faker->dateTimeThisCentury
        ];

        $url = 'http://api.tavro.dev/api/v1/people';

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

        $this->assertEquals(401, $response->getStatusCode());

    }

}