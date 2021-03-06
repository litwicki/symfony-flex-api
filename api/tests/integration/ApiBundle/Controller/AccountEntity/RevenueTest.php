<?php namespace Tests\Integration\ApiBundle\Controller\AccountEntity;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Response;
use Tests\TavroApiTest;

class RevenueTest extends TavroApiTest
{

    public function testRevenueRoute()
    {
        $client = $this->authorize($this->getApiClient());

        $url = '/api/v1/accounts/1/revenues';

        $response = $client->get($url);

        $json = $response->getBody(true);
        $body = json_decode($json, true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    public function testRevenueCreateRevenueWithBadOrganization()
    {

        try {
            $client = $this->authorize($this->getApiClient());

            $data = array(
                'category' => 1,
                'user' => 1,
                'services' => array(1),
                'organization' => -1,
                'account' => 1,
            );

            $url = '/api/v1/accounts/1/revenues';

            $client->post($url, [
                'json' => $data
            ]);
        }
        catch(RequestException $e) {
            $this->assertEquals(Response::HTTP_BAD_REQUEST, $e->getResponse()->getStatusCode());
            $this->assertEquals(1, preg_match('/Please enter a valid Organization/', $e->getMessage()));
        }

    }

}