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
                'AccessToken'       => 'lvprdBVOnMwVECd7t8ANXVoCD0IRpW1u1pqyEXma6AnymkvT',
                'AccessTokenSecret' => 'IqySLEZz5m2yuFDhid3Xvj69T3S9jSllWEcFzIKC',
                'RealmID'           => '123145724021387',
                'DataSource'        => 'QBO'
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


        $response = $oauth->fetch($uri, null, OAUTH_HTTP_METHOD_GET, $headers);

        /**
         * @TODO: The application needs to store the AccessToken and AccessTokenSecret for
         * a particular account, so we can then automate the execution of this command.
         *
         * That part of the OAuth process is handled outside of this command as it requires
         * User interaction via OAuth 1.0 and Quckbooks Online Authorization.
         */

        $qboItems = [

            'Account',
            'Attachable',
            'Batch',
            'Bill',
            'BillPayment',
            'Budget',
            'ChangeDataCapture',
            'Class',
            'CompanyInfo',
            'CreditMemo',
            'Customer',
            'Department',
            'Deposit',
            'Employee',
            'Estimate',
            'Invoice',
            'Item',
            'JournalEntry',
            'Payment',
            'PaymentMethod',
            'Preferences',
            'Purchase',
            'PurchaseOrder',
            'RefundReceipt',
            'Reports',
            'SalesReceipt',
            'TaxAgency',
            'TaxCode',
            'TaxRate',
            'TaxService',
            'Term',
            'TimeActivity',
            'Transfer',
            'Vendor',
            'VendorCredit'

        ];

        foreach($qboItems as $qboItem) {
            $handler = $this->getContainer()->get(sprintf('qbo.handler.%s', $qboItem));
            $response = $handler->import($accountId);
            $messages[] = $response['message'];
            $output->writeln($message);
        }

        return array(
            'data'    => $import,
            'message' => $message,
        );

    }

}
