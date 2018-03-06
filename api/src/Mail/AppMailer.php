<?php namespace App\Mail;

use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AppMailer
{

    use ContainerAwareTrait;

    protected $mailer;
    protected $twig;
    protected $logger;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Define the specifics of an email.
     */
    const EMAIL_MIME_TYPE = 'text/html';

    /**
     * DO NOT OVERRIDE THIS - we will be making this a private constant
     * when we upgrade to PHP 7.1, so be polite and do not touch this in children emails.
     */
    const EMAIL_BUNDLE = 'TavroCoreBundle:Email';

    public function __construct(LoggerInterface $logger)
    {
        //$this->mailer = $mailer;
        $this->logger = $logger;
        //$this->twig = $twig;
    }

//    public function __construct(\Swift_Mailer $mailer, LoggerInterface $logger, TwigEngine $twig)
//    {
//        $this->mailer = $mailer;
//        $this->logger = $logger;
//        $this->twig = $twig;
//    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return mixed
     */
    public function getAppEmail()
    {
        return $this->container->getParameter('app_email');
    }

    /**
     * @return mixed
     */
    public function getAppEmailName()
    {
        return $this->container->getParameter('app_email_name');
    }

    /**
     * Prepare an Email message to be sent.
     *
     * @param array $parameters
     *
     * @return \Swift_Mime_MimePart $message
     * @throws \Exception
     */
    public function prepare(array $parameters = array())
    {

        try {

            $recipients = $this->prepareRecipients($parameters['recipients']);

            $message = \Swift_Message::newInstance()
                ->setSubject($this->prepareSubject())
                ->setFrom($this->prepareFrom())
                ->setTo($recipients)
                ->setBody($this->prepareBody($parameters), self::EMAIL_MIME_TYPE);

            return $message;

        }
        catch (\Exception $e) {
            $this->logger->error(sprintf('Failed preparing email: %s', $e->getMessage()));
            throw $e;
        }

    }

    /**
     * Send the email.
     *  Placeholder intended to be overriden by more advanced child methods.
     *
     * @param array $parameters are the items parsed in the content of the email.
     *
     * @throws \Exception
     */
    public function send(array $parameters = array())
    {
        try {

            $message = $this->prepare($parameters);
            $this->mailer->send($message);

            $log = sprintf('Email "%s" sent to "%s" ...',
                $message->getHeaders()->get('Subject'),
                (count($parameters['recipients']) > 5) ? sprintf('%s recipients', count($parameters['recipients'])) : implode('',$parameters['recipients'])
            );

            $this->logger->info($log);

        }
        catch(\Exception $e) {
            $this->logger->error(sprintf('Failed sending email: %s', $e->getMessage()));
            throw $e;
        }
    }

    /**
     * Prepare the email subject.
     *
     * @param array $parameters
     *
     * @return string
     */
    public function prepareSubject(array $parameters = array())
    {

        if(isset($parameters['subject'])) {
            $subject = sprintf('%s: %s',
                $this->container->getParameter('app_email_name'),
                $parameters['subject']
            );
        }
        else {
            $subject = $this->container->getParameter('app_email_name');
        }

        return $subject;
    }

    /**
     * Prepare the Email body content.
     *
     * @param array $parameters
     *
     * @return string
     * @throws \Exception
     */
    public function prepareBody(array $parameters = array())
    {
        try {
            return $this->twig->render($this->prepareTemplate($parameters), $parameters);
        }
        catch(\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Fetch the email template.
     *
     * @param array $parameters
     *
     * @return string
     * @throws \Exception
     */
    public function prepareTemplate(array $parameters = array())
    {
        $type = (isset($parameters['type'])) ? $parameters['type'] : 'default';
        $template = sprintf('%s:%s.html.twig', self::EMAIL_BUNDLE, $type);

        if(false === $this->twig->exists($template)) {
            $this->logger->error(sprintf('Invalid email template provided: %s', $template));
            throw new \Exception(sprintf('Template `%s` does not exist.', $template));
        }

        return $template;
    }

    /**
     * Make sure the recipients is always an array.
     *
     * @param $recipients
     *
     * @return array
     */
    public function prepareRecipients($recipients)
    {
        return (is_array($recipients) ? $recipients : array($recipients));
    }

    public function prepareFrom()
    {
        return [$this->container->getParameter('app_email') => $this->container->getParameter('app_email_name')];
    }

}
