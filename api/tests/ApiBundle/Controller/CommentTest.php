<?php namespace Tests\ApiBundle\Controller;

use GuzzleHttp\Client;;
use Tests\ApiBundle\TavroApiTest;

class CommentTest extends TavroApiTest
{

    public function testCommentRoute()
    {
        $url = 'https://api.tavro.dev/api/v1/comments/1';

        $token = $this->authorize();

        $client = new Client('https://api.tavro.dev/api/v1', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $request = $client->get($url, null, ['verify' => false]);
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

            $url = 'https://api.tavro.dev/api/v1/revenues/1/comments';

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

        $url = 'https://api.tavro.dev/api/v1/nodes/1/comments';

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

    public function testCommentCreateExpenseComment()
    {
        $token = $this->authorize();

        $data = array(
            'body' => 'Body text..',
            'user' => 1
        );

        $url = 'https://api.tavro.dev/api/v1/expenses/1/comments';

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

    public function testCommentCreateOrganizationComment()
    {
        $token = $this->authorize();

        $data = array(
            'body' => 'Body text..',
            'user' => 1
        );

        $url = 'https://api.tavro.dev/api/v1/organizations/1/comments';

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
die(var_dump($body));
        $this->assertEquals(200, $response->getStatusCode());

    }

}