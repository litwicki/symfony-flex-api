<?php namespace Tavro\Tests\Api\Controller;

use Guzzle\Http\Client;
use Tavro\Bundle\CoreBundle\Testing\TavroTest;

class ExpenseTest extends TavroTest
{

    public function testExpenseRoute()
    {
        $client = new Client('https://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $token = $this->authorize();

        $url = 'https://api.tavro.dev/api/v1/expenses';

        $request = $client->get($url, null, ['verify' => false]);
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testExpenseCreate()
    {

        $token = $this->authorize();

        $faker = \Faker\Factory::create('en_EN');

        $data = array(
            'body' => 'Expense body description.',
            'amount' => 100,
            'expense_date' => $faker->dateTimeThisMonth->format('Y-m-d h:i:s'),
            'user' => 1,
            'account' => 1,
            'category' => 1,
        );

        $url = 'https://api.tavro.dev/api/v1/expenses';

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