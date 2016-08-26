<?php

namespace Tavro\Bundle\CoreBundle\Mail;

use Tavro\Bundle\CoreBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TavroMailer implements ContainerAwareInterface
{
    protected $mailer;
    protected $templating;

    public function __construct($mailer, $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
       $this->container = $container;
    }

    /**
     *  Send an email
     *
     *  @param $params array
     *      Array of parameters for Swiftmailer
     *          subject string
     *          from mixed
     *          recipient array
     *          name string
     *          url string
     *          email string
     *          html true|FALSE
     *
     *  @throws \Exception
     *  @return boolean
     */
    public function send(array $params)
    {

        try {

            /**
             * If a "type" is not defined, assume notice.
             */
            if (!isset($params['type'])) {
                $params['type'] = 'notice';
            }

            if (isset($params['html']) && $params['html'] === true) {
                $contentType = 'text/html';
                $fileType = 'html';
            }
            else {
                $contentType = 'text/plain';
                $fileType = 'txt';
            }

            $template = sprintf('TavroCoreBundle:Email:%s.%s.twig', $params['type'], $fileType);

            $body = $this->templating->render($template, $params);

            /**
             * If email format is plain text, remove any tags from the message body.
             */
            if ($fileType == 'txt') {
                $body = strip_tags($body);
            }

            $subject = sprintf('%s: %s',
                $this->container->getParameter('app_email_name'),
                $params['subject']
            );

            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom(array(
                    'email' => $this->container->getParameter('app_email'),
                    'name'  => $this->container->getParameter('app_email_name')
                ))
                ->setTo($params['recipients'])
                ->setBody($body, $contentType);

            $this->mailer->send($message);

        }
        catch (\Exception $e) {
            throw $e;
        }

    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     * @param bool $html
     *
     * @throws \Exception
     */
    public function sendActivation(User $user, $html = true)
    {
        try {
            $this->send([
                'type' => 'activation',
                'html' => $html,
                'subject' => sprintf('Welcome to %s', $this->container->getParameter('app_name')),
                'recipients' => [$user->getEmail()]
            ]);
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

}
