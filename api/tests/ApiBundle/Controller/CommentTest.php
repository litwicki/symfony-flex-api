<?php namespace Tests\ApiBundle\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Response;

use Tests\ApiBundle\TavroApiTest;

class CommentTest extends TavroApiTest
{

    public function testCommentRoute()
    {
        $url = '/api/v1/comments/1';

        $client = $this->authorize($this->getApiClient());

        $response = $client->get($url);

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    public function testCommentCreateRevenueComment()
    {

        $client = $this->authorize($this->getApiClient());

        $data = [
            'body' => 'Body text..',
            'user' => 1
        ];

        $url = '/api/v1/revenues/1/comments';

        $response = $client->post($url, [
            'json' => $data
        ]);

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

    }

    public function testCommentCreateNodeComment()
    {
        $client = $this->authorize($this->getApiClient());

        $data = array(
            'body' => 'Body text..',
            'user' => 1
        );

        $url = '/api/v1/nodes/1/comments';

        $response = $client->post($url, [
            'json' => $data
        ]);

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

    }

    public function testCommentCreateExpenseComment()
    {
        $client = $this->authorize($this->getApiClient());

        $data = array(
            'body' => 'Body text..',
            'user' => 1
        );

        $url = '/api/v1/expenses/1/comments';

        $response = $client->post($url, [
            'json' => $data
        ]);

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

    }

    public function testCommentCreateOrganizationComment()
    {

        $client = $this->authorize($this->getApiClient());

        $data = array(
            'body' => 'Body text..',
            'user' => 1
        );

        $url = '/api/v1/organizations/1/comments';

        $response = $client->post($url, [
            'json' => $data
        ]);

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

    }

}