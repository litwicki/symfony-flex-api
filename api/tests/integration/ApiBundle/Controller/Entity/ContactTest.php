<?php namespace Tests\ApiBundle\Controller\Entity;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Response;
use Tests\ApiBundle\TavroApiTest;

class ContactTest extends TavroApiTest
{

    public function testContactRoute()
    {
        $client = $this->authorize($this->getApiClient());

        $url = '/api/v1/contacts';

        $response = $client->get($url);

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    public function testContactCreate()
    {

        $client = $this->authorize($this->getApiClient());

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'job_title' => $faker->jobTitle,
            'email' => $faker->email,
            'phone' => '555-867-5309',
            'person' => 1,
            'organization' => 1
        );

        $url = '/api/v1/contacts';


        $response = $client->post($url, [
            'json' => $data
        ]);

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

    }

    public function testContactCreateWithUser()
    {

        $client = $this->authorize($this->getApiClient());

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'job_title' => $faker->jobTitle,
            'email' => $faker->email,
            'phone' => '555-867-5309',
            'person' => 1,
            'user' => 1,
            'organization' => 1
        );

        $url = '/api/v1/contacts';

        $response = $client->post($url, [
            'json' => $data
        ]);

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

    }

    public function testContactCreateBadOrganization()
    {

        try {

            $client = $this->authorize($this->getApiClient());

            $faker = \Faker\Factory::create('en_EN');

            $data = array(
                'job_title' => $faker->jobTitle,
                'email' => $faker->email,
                'phone' => '555-867-5309',
                'person' => 1,
                'user' => 1,
                'organization' => -1
            );

            $url = '/api/v1/contacts';

            $client->post($url, [
                'json' => $data
            ]);

        }
        catch(RequestException $e) {
            $this->assertEquals(Response::HTTP_BAD_REQUEST, $e->getResponse()->getStatusCode());
        }

    }

    public function testContactCreateBadUser()
    {
        try {

            $client = $this->authorize($this->getApiClient());

           $faker = \Faker\Factory::create('en_EN');

           $data = array(
               'name' => $faker->name,
               'body' => $faker->text(500),
               'user' => -1,
               'organization' => 1
           );

           $url = '/api/v1/contacts';

           $client->post($url, [
               'json' => $data
           ]);

        }
        catch(RequestException $e) {
            $this->assertEquals(Response::HTTP_BAD_REQUEST, $e->getResponse()->getStatusCode());
        }

    }

}