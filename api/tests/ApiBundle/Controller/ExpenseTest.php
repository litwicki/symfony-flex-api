<?php namespace Tests\ApiBundle\Controller;

use GuzzleHttp\Client;
use Tests\ApiBundle\TavroApiTest;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Exception\RequestException;

class ExpenseTest extends TavroApiTest
{

    public function testExpenseRoute()
    {

        $client = $this->authorize($this->getApiClient());

        $url = '/api/v1/expenses';

        $response = $client->get($url);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    public function testExpenseCreate()
    {

        $client = $this->authorize($this->getApiClient());

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'body' => $faker->text(500),
            'amount' => $faker->numberBetween(1,100),
            'expense_date' => $faker->dateTimeThisMonth->format('Y-m-d h:i:s'),
            'user' => 1,
            'account' => 1,
            'category' => 1,
        );

        $url = '/api/v1/expenses';

        $response = $client->post($url, [
            'json' => $data
        ]);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

    }

    public function testExpenseCreateBadUser()
    {

        try {
            $client = $this->authorize($this->getApiClient());

            $faker = \Faker\Factory::create('en_EN');

            $data = array(
                'body' => $faker->text(500),
                'amount' => $faker->numberBetween(1,100),
                'expense_date' => $faker->dateTimeThisMonth->format('Y-m-d h:i:s'),
                'user' => -1,
                'account' => 1,
                'category' => 1,
            );

            $url = '/api/v1/expenses';

            $client->post($url, [
                'json' => $data
            ]);
        }
        catch(RequestException $e) {
            $this->assertEquals(Response::HTTP_BAD_REQUEST, $e->getResponse()->getStatusCode());
        }

    }

    public function testExpenseCreateBadAccount()
    {

        try {
            $client = $this->authorize($this->getApiClient());

            $faker = \Faker\Factory::create('en_EN');

            $data = array(
                'body' => $faker->text(500),
                'amount' => $faker->numberBetween(1,100),
                'expense_date' => $faker->dateTimeThisMonth->format('Y-m-d h:i:s'),
                'user' => 1,
                'account' => -1,
                'category' => 1,
            );

            $url = '/api/v1/expenses';

            $response = $client->post($url, [
                'json' => $data
            ]);
        }
        catch(RequestException $e) {
            $this->assertEquals(Response::HTTP_BAD_REQUEST, $e->getResponse()->getStatusCode());
        }

    }

    public function testExpenseCreateBadCategory()
    {

        try {
            $client = $this->authorize($this->getApiClient());

            $faker = \Faker\Factory::create('en_EN');

            $data = array(
                'body' => $faker->text(500),
                'amount' => $faker->numberBetween(1,100),
                'expense_date' => $faker->dateTimeThisMonth->format('Y-m-d h:i:s'),
                'user' => 1,
                'account' => 1,
                'category' => -1,
            );

            $url = '/api/v1/expenses';

            $response = $client->post($url, [
                'json' => $data
            ]);
        }
        catch(RequestException $e) {
            $this->assertEquals(Response::HTTP_BAD_REQUEST, $e->getResponse()->getStatusCode());
        }

    }

}