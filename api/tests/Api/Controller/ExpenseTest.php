<?php namespace Tests\Api\Controller;

use Guzzle\Http\Client;
use Tavro\Bundle\CoreBundle\Testing\TavroTest;

class ExpenseTest extends TavroTest
{

    public function testExpenseRoute()
    {
        $client = new Client('http://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $token = $this->authorize();

        $url = 'http://api.tavro.dev/api/v1/expenses';

        $request = $client->get($url);
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testExpenseCreate()
    {

        $token = $this->authorize();

        $date = new \DateTime();

        $data = array(
            'body' => 'Expense body description.',
            'user' => 1,
            'organization' => 1,
            'expense_date' => $date->format('Y-m-d h:i:s'),
            'amount' => 100
        );

        $url = 'http://api.tavro.dev/api/v1/expenses';

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


        $this->assertEquals(200, $response->getStatusCode());

    }

}