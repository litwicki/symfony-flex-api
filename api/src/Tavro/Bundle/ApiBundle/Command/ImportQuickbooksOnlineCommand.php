<?php

namespace Tavro\Bundle\ApiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Litwicki\Common\cURL;

use Tavro\Bundle\CoreBundle\Entity\Variable;

class ImportQuickbooksOnlineCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('tavro:import:qbo')->setDescription('Import and synchronize a QBO Account with a Tavro Account.')->addOption('account', NULL, InputOption::VALUE_NONE, 'Account Id');
    }

    /**
     * @return string
     */
    protected function getUri()
    {
        return 'https://sandbox-quickbooks.api.intuit.com/v3';
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $import = array();

        $message = '';

        $accountId = 0;

        /**
         * This is sloppy, but Symfony3 can't make up its mind if we need to use handleRequest
         * or simply submit() on a given FormType, so we have Request as a parameter for our
         * data handlers and pass it via controllers, but in Commands (for now anyway) we will
         * set to NULL and hope for the best..
         */
        $request = NULL;

        if ($input->getOption('option')) {
            $accountId = $input->getOption('account');
        }

        $em = $this->getContainer()->get('doctrine')->getEntityManager();

        $vars = [
            'ConsumerKey',
            'ConsumerSecret',
            'AccessToken',
            'AccessTokenSecret',
            'RealmID'
        ];

        $configs = array();

        $em = $this->getContainer()->get('doctrine')->getEntityManager();

        foreach($vars as $key) {

            $configs[$key] = $em->createQuery("SELECT v.value FROM TavroCoreBundle:Variable v WHERE v.account_id=:account AND v.name=qbo.:key")
                ->setParameter('account', $accountId)
                ->setParameter('key', $key)
                ->getSingleScalarResult();

        }

        $uri = sprintf('%s/%s', $this->getUri(), $configs['RealmID']);

        //        <add key="AccessToken" value="qyprdBEjbHRQ385zcxsONA3kFLRYjhvaaj6Jh8yUg6L5ntoI" />
        //        <add key="AccessTokenSecret" value="BKOKIqK2yL2ixHoBXUDylb1S3cSjojPH4VndDMn1" />
        //        <add key="ConsumerKey" value="qyprdhtIFrV109VHku2kVAv9B02K6u" />
        //        <add key="ConsumerSecret" value="PqofHTPE6GT65EqaX0nPQwGVghmv3C4RZQZfeIeX" />
        //        <add key="RealmID" value="123145809324582" />

        $output->writeln('');

        $oauth = new \OAuth($configs['ConsumerKey'], $configs['ConsumerSecret']);
        $oauth->setToken($configs['AccessToken'], $configs['AccessTokenSecret']);
        $oauth->enableDebug();
        $oauth->setAuthType(OAUTH_AUTH_TYPE_AUTHORIZATION);
        $oauth->disableSSLChecks();

        $headers = array('accept' => 'application/json');

        /**
         * @TODO: build the requestBody
         *      for example: $requestBody = 'SELECT * FROM Customer';
         */

        $uri = sprintf('%s/%s?query=%s');

        $oauth->fetch($uri, null, OAUTH_HTTP_METHOD_GET, $headers);

        return array(
            'data'    => $import,
            'message' => $message,
        );

    }

}
