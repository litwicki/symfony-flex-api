<?php namespace Tests\Unit\CoreBundle\Mail;

use GuzzleHttp\Client;
use Tavro\Bundle\CoreBundle\Calculator\Churn;
use Tavro\Bundle\CoreBundle\Entity\User;

use Tests\SymfonyKernel;

class TavroMailerTest extends \PHPUnit_Framework_TestCase
{
    use SymfonyKernel;

    public function testPrepare()
    {
        $faker = \Faker\Factory::create('en_EN');

        $email = $faker->safeEmail;

        $message = \Swift_Message::newInstance()
            ->setSubject($faker->text(200))
            ->setFrom([$email => $faker->name])
            ->setTo([$email])
            ->setBody($faker->text(5000), 'text/plain');

        $this->assertTrue(($message instanceof \Swift_Message), 'Message ($message) must be of type Swift_Message (swiftmailer)');
    }

    public function testPrepareBodyHtml()
    {
        $template = 'TavroCoreBundle:Email:default.html.twig';
        $body = $this->container->get('templating')->render($template, [
            'app_name' => 'Tavro',
            'message' => 'hello world'
        ]);

        $this->assertContains('hello world', $body, 'Rendered HTML does not contain expected message.');
        $this->assertTrue($body != strip_tags($body), 'Body should be HTML.');
    }

    public function testPrepareBodyTxt()
    {
        $template = 'TavroCoreBundle:Email:default.html.twig';
        $body = $this->container->get('templating')->render($template, [
            'app_name' => 'Tavro',
            'message' => 'hello world'
        ]);

        $this->assertContains('hello world', $body, 'Rendered HTML does not contain expected message.');
    }

    public function testPrepareSubject()
    {
        $faker = \Faker\Factory::create('en_EN');

        $subject = sprintf('%s: %s',
            $faker->company,
            $faker->text(500)
        );

        $this->assertNotNull($subject, 'Subject cannot be NULL');
    }

    public function testPrepareTemplateHtml()
    {
        $template = 'TavroCoreBundle:Email:default.html.twig';
        $message = sprintf('Could not find %s', $template);
        $this->assertTrue(true === $this->container->get('templating')->exists($template), $message);
    }

    public function testPrepareTemplateTxt()
    {
        $template = 'TavroCoreBundle:Email:default.html.twig';
        $message = sprintf('Could not find %s', $template);
        $this->assertTrue(true === $this->container->get('templating')->exists($template), $message);
    }

    public function testPrepareRecipients()
    {
        $faker = \Faker\Factory::create('en_EN');

        $recipients = [
            $faker->safeEmail,
        ];

        $this->assertTrue(is_array($recipients), 'Recipients must be array.');
        $this->assertNotEmpty($recipients, 'Recipients cannot be an empty array.');
    }

}