<?php namespace Tests\Api\Controller;

use Guzzle\Http\Client;
use Tavro\Bundle\CoreBundle\Testing\TavroTest;

class CommentTest extends TavroTest
{

    public function testCommentRoute()
    {
        $url = 'http://api.tavro.dev/api/v1/comments/1';

        $token = $this->authorize();

        $client = new Client('http://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $request = $client->get($url);
        $request->addHeader('Authorization', sprintf('Bearer %s', $token));
        $response = $request->send();

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testCommentCreateRevenueComment()
    {
        try {

            $token = $this->authorize();

            $data = [
                'body' => 'Body text..',
                'user' => 1
            ];

            $url = 'http://api.tavro.dev/api/v1/revenues/1/comments';

            $client = new Client($url, array(
                'request.options' => array(
                    'exceptions' => false,
                )
            ));

            $request = $client->post($url, NULL, json_encode($data));
            $request->addHeader('Authorization', sprintf('Bearer %s', $token));
            $response = $request->send();

            $json = $response->getBody(true);
            $body = json_decode($json, true);

            $this->assertEquals(200, $response->getStatusCode());

        }
        catch(\Exception $e) {
            throw $e;
        }

    }

    public function testCommentCreateNodeComment()
    {
        $token = $this->authorize();

        $data = array(
            'body' => 'Body text..',
            'user' => 1
        );

        $url = 'http://api.tavro.dev/api/v1/nodes/1/comments';

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

    public function testCommentCreateExpenseComment()
    {
        $token = $this->authorize();

        $data = array(
            'body' => 'Body text..',
            'user' => 1
        );

        $url = 'http://api.tavro.dev/api/v1/expenses/1/comments';

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

    public function testCommentCreateCustomerComment()
    {
        $token = $this->authorize();

        $data = array(
            'body' => 'Body text..',
            'user' => 1
        );

        $url = 'http://api.tavro.dev/api/v1/customers/1/comments';

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