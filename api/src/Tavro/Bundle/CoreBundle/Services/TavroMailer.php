<?php

namespace Tavro\Bundle\CoreBundle\Services;

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
     *          html true|false
     *
     *  @throws \Exception
     *  @return boolean
     */
    public function sendEmail(array $params)
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
             * If email format is plain text, remove any tags from the message.
             */
            if ($fileType == 'txt') {
                $body = strip_tags($body);
            }

            if(isset($params['from'])) {
                $from = $params['from'];
            }
            else {
                $from = array(
                    'email' => $this->container->getParameter('app_email'),
                    'name'  => $this->container->getParameter('app_email_name')
                );
            }

            $subject = sprintf('%s: %s',
                $this->container->getParameter('app_email_name'),
                $params['subject']
            );

            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($from)
                ->setTo($params['recipients'])
                ->setBody($body, $contentType);

            $this->mailer->send($message);

        }
        catch (\Exception $e) {
            throw $e;
        }

    }

}
