<?php namespace Tavro\Bundle\ApiBundle\QuickbooksOnline\Handler;

use Doctrine\ORM\EntityManager;
use \OAuth;

use Tavro\Bundle\CoreBundle\Entity\Expense;

use Tavro\Bundle\ApiBundle\QuickbooksOnline\QboApiService;

/**
 * Class PurchaseHandler
 *
 * Parse Purchases into Expense Entities
 *
 * @package Tavro\Bundle\ApiBundle\QuickbooksOnline\Handler
 */
class PurchaseHandler extends QboApiService
{

    public function import($id, $debug = true)
    {

        $account = $this->em->getRepository('TavroCoreBundle:Account')->find($id);
        $oauth = $this->connect($account);
        $purchaseCount = 0;

        /**
         * @TODO: Should we filter out records that already have a qbo_id??
         */
        $query = 'SELECT * FROM Purchase';

        $url = sprintf('%s/company/%s/query?query=%s',
            $this->getBaseUrl($debug),
            $this->realmId,
            $this->buildQuery($query)
        );

        $oauth->fetch($url);
        $response = $oauth->getLastResponse();

        $purchases = $response['QueryResponse']['Customer'];

        $expenses = array();

        foreach ($purchases as $purchase) {

            $txnDate = $purchase['TxnDate'];

            $lineItems = $purchase['Line'];

            foreach($lineItems as $lineItem) {

                $expense = $this->em->getRepository('TavroCoreBundle:Expense')->findOneBy([
                    'account' => $account->getId(),
                    'qbo_id' => $lineItem['Id']
                ]);

                if(!$expense instanceof Expense) {

                    $expenses[] = $this->container->get('tavro.handler.expenses')->create([
                        'body' => $lineItem['Description'],
                        'expense_date' => $txnDate,
                        'account' => $account->getId(),
                        'amount' => $lineItem['Amount'],
                        'qbo_id' => $lineItem['Id']
                    ]);

                }

            }

        }

        $message = sprintf('%s Expenses processed', count($expenses));

        return [
            'data' => $expenses,
            'message' => $message
        ];

    }

}