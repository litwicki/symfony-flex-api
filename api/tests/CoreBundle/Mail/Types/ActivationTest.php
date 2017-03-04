<?php namespace Tests\CoreBundle\Mail;

use GuzzleHttp\Client;
use Tavro\Bundle\CoreBundle\Calculator\Churn;
use Tavro\Bundle\CoreBundle\Entity\User;
use Tests\CoreBundle\TavroCoreTest;
use Tests\SymfonyKernel;

class TavroMailerTest extends TavroCoreTest
{
    use SymfonyKernel;


    public function testPrepareRecipients()
    {

        $appEmail = $this->container->getParameter('app_email');

        $recipients = [
            $appEmail
        ];

        $this->assertTrue(is_array($recipients), 'Recipients must be array.');
        $this->assertNotEmpty($recipients, 'Recipients cannot be an empty array.');
        $this->assertContains($appEmail, $recipients, sprintf('%s must be a recipient for activation emails.', $appEmail));
    }

}