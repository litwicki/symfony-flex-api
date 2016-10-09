<?php namespace Tavro\Bundle\ApiBundle\QuickbooksOnline\Handler;

use Doctrine\ORM\EntityManager;
use \OAuth;

use Tavro\Bundle\CoreBundle\Entity\Account;
use Tavro\Bundle\ApiBundle\QuickbooksOnline\QboApiService;

class SalesReceiptHandler extends QboApiService
{

    public function import($id, $debug = true)
    {

        $account = $this->em->getRepository('TavroCoreBundle:Account')->find($id);
        $oauth = $this->connect($account);

        $query = 'SELECT * FROM SalesReceipt';

        $url = sprintf('%s/company/%s/query?query=%s',
            $this->getBaseUrl($debug),
            $this->realmId,
            $this->buildQuery($query)
        );

        $response = $oauth->fetch($url);


    }

}