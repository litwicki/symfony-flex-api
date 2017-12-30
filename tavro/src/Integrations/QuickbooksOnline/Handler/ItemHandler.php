<?php namespace Tavro\QuickbooksOnline\Handler;

use Doctrine\ORM\EntityManager;
use \OAuth;

use Tavro\Entity\Organization;
use Tavro\Entity\Product;
use Tavro\Entity\Service;
use Tavro\Entity\Account;

use Tavro\QuickbooksOnline\QboApiService;

/**
 * Class ItemHandler
 *
 * Parse Items into Products and Services
 *
 * @package Tavro\QuickbooksOnline\Handler
 */
class ItemHandler extends QboApiService
{

    public function import($id, $debug = true)
    {

        $account = $this->em->getRepository('TavroCoreBundle:Account')->find($id);
        $oauth = $this->connect($account);
        $productCount = $serviceCount = $revenueCount = 0;

        /**
         * @TODO: Should we filter out records that already have a qbo_id??
         */
        $query = 'SELECT * FROM Item';

        $url = sprintf('%s/company/%s/query?query=%s',
            $this->getBaseUrl($debug),
            $this->realmId,
            $this->buildQuery($query)
        );

        $oauth->fetch($url);
        $response = $oauth->getLastResponse();

        $items = $response['QueryResponse']['Item'];
        $entities = $products = $services = array();

        foreach ($items as $item) {

            $id = $item['Id'];

            $type = $item['Type'];

            switch($type) {

                case 'Inventory':
                    if(!$this->checkProduct($account, $id)) {
                        $products[] = $this->createProduct($account, $item);
                        $products++;
                    }
                    break;
                case 'Service':
                    if(!$this->checkService($account, $id)) {
                        $services[] = $this->createService($account, $item);
                        $services++;
                    }
                    break;
                default:
                    break;

            }

        }

        $message = sprintf('%s Products, %s Services were processed.', count($products), count($services));

        return [
            'data' => $entities,
            'message' => $message
        ];

    }

    /**
     * Do we have a Product with this QboId already?
     *
     * @param $qboId
     *
     * @return bool
     */
    public static function checkProduct(Account $account, $qboId)
    {
        $entity = self::$em->getRepository('TavroCoreBundle:Product')->findOneBy([
            'account' => $account->getId(),
            'qbo_id' => $qboId
        ]);

        return $entity instanceof Product;
    }

    /**
     * @param \Tavro\Entity\Account $account
     * @param array $item
     *
     * @throws \Exception
     */
    public static function createProduct(Account $account, array $item)
    {
        try {

            $product = self::$container->get('tavro.handler.products')->create([
                'name' => $item['Name'],
                'body' => $item['Description'],
                'status' => $item['Active'] == 'true' ? Product::STATUS_ENABLED : Product::STATUS_DISABLED,
                'account' => $account->getId(),
                'price' => $item['UnitPrice'],
                'cost' => $item['PurchaseCost']
            ]);

            if(!$product instanceof Product) {
                throw new \Exception('Unable to create new product with provided information');
            }

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Do we have a Service with this QboId already?
     *
     * @param $qboId
     *
     * @return bool
     */
    public static function checkService(Account $account, $qboId)
    {
        $entity = self::$em->getRepository('TavroCoreBundle:Service')->findOneBy([
            'account' => $account->getId(),
            'qbo_id' => $qboId
        ]);

        return $entity instanceof Product;
    }

    /**
     * @param \Tavro\Entity\Account $account
     * @param array $item
     *
     * @throws \Exception
     */
    public static function createService(Account $account, array $item)
    {
        try {

            $product = self::$container->get('tavro.handler.products')->create([
                'name' => $item['Name'],
                'body' => $item['Description'],
                'status' => $item['Active'] == 'true' ? Product::STATUS_ENABLED : Product::STATUS_DISABLED,
                'account' => $account->getId(),
                'price' => $item['UnitPrice']
            ]);

            if(!$product instanceof Product) {
                throw new \Exception('Unable to create new product with provided information');
            }

        }
        catch(\Exception $e) {
            throw $e;
        }
    }


}