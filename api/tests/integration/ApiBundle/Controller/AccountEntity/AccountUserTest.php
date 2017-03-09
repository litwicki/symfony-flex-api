<?php namespace Tests\Integration\ApiBundle\Controller\AccountEntity;

use GuzzleHttp\Client;
use Tests\Integration\ApiBundle\TavroApiTest;
use Symfony\Component\HttpFoundation\Response;

class AccountUserTest extends TavroApiTest
{

    public function testAccountUserRoute()
    {
        $client = $this->authorize($this->getApiClient());

        $url = '/api/v1/accounts/1/users';

        $response = $client->get($url);

        $json = $response->getBody();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }

    public function testAccountUserDelete()
    {
        $client = $this->authorize($this->getApiClient());

        $url = '/api/v1/accounts/1/users';

        /**
         * @TODO: this assertion/test
         */

    }

}