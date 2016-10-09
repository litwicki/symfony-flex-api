<?php namespace Tavro\Bundle\ApiBundle\QuickbooksOnline;

use Doctrine\ORM\EntityManager;
use \OAuth;

use Tavro\Bundle\CoreBundle\Entity\Account;

class QboApiService
{
    protected $em;
    protected $object = 'SalesReceipt';
    protected $realmId;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getBaseUrl($debug = false)
    {
        return $debug ? 'https://sandbox-quickbooks.api.intuit.com/v3/' : 'https://quickbooks.api.intuit.com/v3/';
    }

    public function connect(Account $account)
    {

        try {

            $configs = [];

            $vars = [
                'ConsumerKey',
                'ConsumerSecret',
                'AccessToken',
                'AccessTokenSecret',
                'RealmID'
            ];

            foreach($vars as $key) {

                $configs[$key] = $this->em->createQuery("SELECT v.value FROM TavroCoreBundle:Variable v WHERE v.account_id=:account AND v.name=qbo.:key")
                    ->setParameter('account', $account->getId())
                    ->setParameter('key', $key)
                    ->getSingleScalarResult();

            }

            $this->realmId = $configs['RealmID'];

            $oauth = new Oauth($configs['ConsumerKey'], $configs['ConsumerSecret']);
            $oauth->enableDebug();
            $oauth->disableSSLChecks();
            $oauth->setToken($configs['AccessToken'], $configs['AccessTokenSecret']);

            return $oauth;
        }
        catch(\Exception $e) {
            throw $e;
        }

    }

    public function buildQuery($query)
    {
        $query = urlencode($query);
        $query = str_replace('+', '%20', $query);
        return $query;
    }

}