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
        $this->setName('tavro:import:qbo')
            ->setDescription('Import and synchronize a QBO Account with a Tavro Account.')
            ->addOption('account', NULL, InputOption::VALUE_REQUIRED, 'Which Account are we running this import for?', 0);
    }

    //define('OAUTH_REQUEST_URL', 'https://oauth.intuit.com/oauth/v1/get_request_token');
    //define('OAUTH_ACCESS_URL', 'https://oauth.intuit.com/oauth/v1/get_access_token');
    //define('OAUTH_AUTHORISE_URL', 'https://appcenter.intuit.com/Connect/Begin');

    /**
     * @return string
     */
    protected function getUri($debug)
    {
        return $debug ? 'https://sandbox-quickbooks.api.intuit.com/v3/company' : 'https://quickbooks.api.intuit.com/v3/company';
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $import = array();

        $message = '';

        $accountId = 0;

        /**
         * We're in debug mode, unless no-debug was set
         */
        $debug = !$input->getOption('no-debug');

        /**
         * This is sloppy, but Symfony3 can't make up its mind if we need to use handleRequest
         * or simply submit() on a given FormType, so we have Request as a parameter for our
         * data handlers and pass it via controllers, but in Commands (for now anyway) we will
         * set to NULL and hope for the best..
         */
        $request = NULL;

        if ($input->getOption('account')) {
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

        if($debug) {

            $configs = [
                'ConsumerKey'       => 'qyprdGN04Ss2F4XEidMdT6YzY1EFBU',
                'ConsumerSecret'    => 'EkQ6VmzrqZYgmDhKZ8AeUzW8rHYIpGPZHla4KU4i',
                'AccessToken'       => 'lvprd9YuaBzqaS5mTRhptPyghRXbgismAdIhiUpu0hnT93po',
                'AccessTokenSecret' => 'c8OnwLWIhhI02ZvrekxJ6xDQtl8ndYZINGby7AZB',
                'RealmID'           => '123145724021387'
            ];

        }
        else {

            $em = $this->getContainer()->get('doctrine')->getEntityManager();

            foreach($vars as $key) {

                $configs[$key] = $em->createQuery("SELECT v.value FROM TavroCoreBundle:Variable v WHERE v.account_id=:account AND v.name=qbo.:key")
                    ->setParameter('account', $accountId)
                    ->setParameter('key', $key)
                    ->getSingleScalarResult();

            }

        }

        $oauth = new \OAuth($configs['ConsumerKey'], $configs['ConsumerSecret'], OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
        $oauth->enableDebug();
        $oauth->disableSSLChecks();
        $requestToken = $oauth->getRequestToken( 'https://oauth.intuit.com/oauth/v1/get_request_token', 'http://api.tavro.dev' );

        $client   = $this->getContainer()->get('guzzle.client');

        // GET request with parameters
        $tokenRequestUri = sprintf('https://appcenter.intuit.com/Connect/Begin?oauth_token=%s', $requestToken);
        $response = $client->get($tokenRequestUri);
        $code = $response->getStatusCode();
        $body = $response->getBody();

        die(dump($body));

        $oauth->setToken($configs['AccessToken'], $configs['AccessTokenSecret']);
        $oauth->enableDebug();
        $oauth->setAuthType(OAUTH_AUTH_TYPE_AUTHORIZATION);
        $oauth->disableSSLChecks();

        /**
         * @TODO: build the requestBody
         *      for example: $requestBody = 'SELECT * FROM Customer';
         */

        $query = 'SELECT * FROM Customer';

        $uri = sprintf('%s/%s/query?query=%s',
            $this->getUri($debug),
            $configs['RealmID'],
            urlencode(str_replace('+', '%20', $query))
        );

        //$url = 'https://sandbox-quickbooks.api.intuit.com/v3/company/123145724021387/query?query=SELECT%20%2A%20FROM%20Customer&minorversion=4';

        $result = $oauth->fetch($uri, null, OAUTH_HTTP_METHOD_GET, [
            'accept' => 'application/json'
        ]);

        $response = $oauth->getLastResponse();

        dump([$uri, json_decode($response, true)]);
        die(__METHOD__);

        return array(
            'data'    => $import,
            'message' => $message,
        );

    }

}
