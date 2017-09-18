<?php namespace Tavro\Bundle\ApiBundle\QuickbooksOnline;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use \OAuth;

use Tavro\Bundle\CoreBundle\Entity\Account;

class QboApiService implements ContainerAwareInterface
{
    protected $container;

    protected $em;
    protected $realmId;
    protected $tokenStorage;

    /**
     * QboApiService constructor.
     *
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(EntityManager $em, TokenStorage $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param bool $debug
     *
     * @return string
     */
    public function getBaseUrl($debug = false)
    {
        return $debug ? 'https://sandbox-quickbooks.api.intuit.com/v3/' : 'https://quickbooks.api.intuit.com/v3/';
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     *
     * @return \OAuth
     * @throws \Exception
     */
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

    /**
     * @param $query
     *
     * @return mixed|string
     */
    public function buildQuery($query)
    {
        $query = urlencode($query);
        $query = str_replace('+', '%20', $query);
        return $query;
    }

    public function getUser()
    {
        return $this->tokenStorage->getToken()->getUser();
    }

}