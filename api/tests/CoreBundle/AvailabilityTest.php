<?php namespace Tests\CoreBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AvailabilityTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', 'http://api.tavro.dev/');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

}