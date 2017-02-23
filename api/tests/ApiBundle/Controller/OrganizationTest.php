<?php namespace Tavro\Tests\ApiBundle\Controller;

use GuzzleHttp\Client;;
use Tavro\Tests\ApiBundle\TavroApiTest;

class OrganizationTest extends TavroApiTest
{

    public function testOrganizationRoute()
    {
        $client = new Client('/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $token = $this->authorize();

        $url = '/api/v1/organizations';

        $request = $client->get($url, null, ['verify' => false]);
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testOrganizationCreate()
    {

        $faker = \Faker\Factory::create('en_EN');

        $token = $this->authorize();

        $data = array(
            'name' => $faker->name,
            'body' => $faker->text(rand(100,1000)),
            'address' => $faker->address,
            'city' => $faker->city,
            'state' => 'WA',
            'zip' => $faker->postcode,
            'website' => $faker->url,
            'phone' => '555-867-5309',
            'account' => 1
        );

        $url = '/api/v1/organizations';

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