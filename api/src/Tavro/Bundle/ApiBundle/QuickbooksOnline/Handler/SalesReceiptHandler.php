<?php namespace Tavro\Bundle\ApiBundle\QuickbooksOnline\Handler;

use Doctrine\ORM\EntityManager;
use \OAuth;

use Tavro\Bundle\CoreBundle\Entity\Account;
use Tavro\Bundle\CoreBundle\Entity\Service;
use Tavro\Bundle\CoreBundle\Entity\Product;

use Tavro\Bundle\ApiBundle\QuickbooksOnline\QboApiService;

class SalesReceiptHandler extends QboApiService
{

    public function import($id, $debug = true)
    {

        $account = $this->em->getRepository('TavroCoreBundle:Account')->find($id);
        $oauth = $this->connect($account);
        $productCount = $serviceCount = $revenueCount = 0;

        /**
         * @TODO: Should we filter out records that already have a qbo_id??
         */
        $query = 'SELECT * FROM SalesReceipt';

        $url = sprintf('%s/company/%s/query?query=%s',
            $this->getBaseUrl($debug),
            $this->realmId,
            $this->buildQuery($query)
        );

        $oauth->fetch($url);
        $response = $oauth->getLastResponse();

        $salesReceipts = $response['QueryResponse']['SalesReceipt'];

        foreach($salesReceipts as $item) {

            /**
             * For Each SalesReceipt we need to parse out the Revenue by determining what
             * Products and/or Services went into the Sale; or in our case Revenue
             */
            $user = $this->getUser();

            $products = $services = array();

            $lineItems = $item['Line'];

            foreach($lineItems as $lineItem) {

                $type = $lineItem['DetailType'];
                $name = $lineItem['Description'];

                /**
                 * We can determine if this is a Product or Service based on
                 * the TaxCodeRef value;
                 *
                 * NON = non taxable service
                 */

                if( $type == 'SalesItemLineDetail' ) {

                    $salesItemLineDetail = $lineItem['SalesItemLineDetail'];
                    $taxCodeRef = $salesItemLineDetail['TaxCodeRef']['value'];

                    if($taxCodeRef == 'NON') {

                        $service = $this->em->getRepository('TavroCoreBundle:Service')->findOneBy([
                            'account' => $account->getId(),
                            'qbo_id' => $lineItem['Id'],
                        ]);

                        /**
                         * If we can't find a Service by this name, create a new one.
                         */
                        if(!$service instanceof Service) {

                            $service = $this->container->get('tavro.handler.services')->create([
                                'name' => $name,
                                'type' => 'QBO',
                                'price' => $salesItemLineDetail['UnitPrice'],
                                'account' => $account->getId(),
                                'category' => 1,
                                'qbo_id' => $lineItem['Id']
                            ]);

                        }

                    }

                    $services[] = $service;
                    $serviceCount++;

                }

                /**
                 * @TODO: find a way to distinguish Products..
                 */


            }

            /**
             * What "Type" of Revenue is this?
             */

            $revenues[] = $this->container->get('tavro.handler.revenues')->create([

                'body' => $item['Description'],
                'type' => '',
                'category' => '',
                'user' => $user,
                'services' => $services,
                'products' => $products
            ]);

        }

        $message = sprintf('Imported %s new Revenue items with %s Products and %s Services',
            count($revenues),
            $productCount,
            $serviceCount
        );

        return [
            'data' => $revenues,
            'message' => $message
        ];

    }

}