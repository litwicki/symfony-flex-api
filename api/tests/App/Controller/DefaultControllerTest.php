<?php namespace Tests\App\Controller;

use Guzzle\Http\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @group App
     */
    public function testRemoveTrailingSlashAction()
    {
        //@TODO: This...
    }

    /**
     * @group App
     */
    public function testIndexAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'http://api.tavro.dev');

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("TAVRO API Documentation")')->count()
        );
    }
}