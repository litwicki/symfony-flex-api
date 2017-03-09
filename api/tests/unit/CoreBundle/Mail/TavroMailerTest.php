<?php namespace Tests\CoreBundle\Mail;

use GuzzleHttp\Client;
use Tavro\Bundle\CoreBundle\Calculator\Churn;
use Tavro\Bundle\CoreBundle\Entity\User;
use Tests\CoreBundle\TavroCoreTest;
use Tests\SymfonyKernel;

class TavroMailerTest extends TavroCoreTest
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
        $faker = \Faker\Factory::create('en_EN');
        $name = $faker->name;

        $template = __DIR__ . '/twig/email.html.twig';
        $body = $this->container->get('templating')->render($template, [
            'name' => $name
        ]);

        $this->assertNotNull($body, 'Body cannot be NULL');
        $this->assertContains($name, $body, sprintf('Body expected to contain replaced string %s.', $name));
    }

    public function testPrepareBodyTxt()
    {
        $faker = \Faker\Factory::create('en_EN');
        $name = $faker->name;

        $template = __DIR__ . '/twig/email.txt.twig';
        $body = $this->container->get('templating')->render($template, [
            'name' => $name
        ]);

        $this->assertNotNull($body, 'Body cannot be NULL');
        $this->assertTrue(($body == sprintf('Hello %s', $name)), 'Expecting raw plain text body.');
        $this->assertContains($name, $body, sprintf('Body expected to contain replaced string %s.', $name));
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
        $template = __DIR__ . '/twig/email.html.twig';
        $this->assertTrue(true === $this->container->get('templating')->exists($template), 'Expecting Twig to verify existence of HTML template.');
    }

    public function testPrepareTemplateTxt()
    {
        $template = __DIR__ . '/twig/email.txt.twig';
        $this->assertTrue(true === $this->container->get('templating')->exists($template), 'Expecting Twig to verify existence of plain-text template.');
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