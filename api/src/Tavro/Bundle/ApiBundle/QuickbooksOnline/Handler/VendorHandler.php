<?php namespace Tavro\Bundle\ApiBundle\QuickbooksOnline\Handler;

use Doctrine\ORM\EntityManager;
use \OAuth;

use Tavro\Bundle\CoreBundle\Entity\Account;
use Tavro\Bundle\CoreBundle\Entity\Service;
use Tavro\Bundle\CoreBundle\Entity\Product;

use Tavro\Bundle\ApiBundle\QuickbooksOnline\QboApiService;

class VendorHandler extends QboApiService
{

    public function import($id, $debug = true)
    {

        $account = $this->em->getRepository('TavroCoreBundle:Account')->find($id);
        $oauth = $this->connect($account);
        $productCount = $serviceCount = $revenueCount = 0;

        /**
         * @TODO: Should we filter out records that already have a qbo_id??
         */
        $query = 'SELECT * FROM Vendor';

        $url = sprintf('%s/company/%s/query?query=%s',
            $this->getBaseUrl($debug),
            $this->realmId,
            $this->buildQuery($query)
        );

        $oauth->fetch($url);
        $response = $oauth->getLastResponse();

        $vendors = $response['QueryResponse']['Vendor'];

        foreach ($vendors as $vendor) {

            $id = $vendor['Id'];

            $entity = $this->em->getRepository('TavroCoreBundle:Organization')->findOneBy([
                'account' => $account->getId(),
                'qbo_id' => $id
            ]);

            $entities[] = $this->container->get('tavro.handler.organizations')->create([

                'name' => $vendor['DisplayName'],
                'account' => $account->getId(),
                'qbo_id' => $id

            ]);

        }

        $message = sprintf('%s Vendors were processed.', count($entities));

        return [
            'data' => $entities,
            'message' => $message
        ];

    }

}