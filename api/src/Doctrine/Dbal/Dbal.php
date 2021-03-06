<?php

namespace App\Doctrine\Dbal;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Dbal implements ContainerAwareInterface
{

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function __construct()
    {

    }

    public function getConnection()
    {
        $config = new \Doctrine\DBAL\Configuration();

        $params = array(
            'dbname' => $this->container->getParameter('database_name'),
            'user' => $this->container->getParameter('database_user'),
            'password' => $this->container->getParameter('database_password'),
            'host' => $this->container->getParameter('database_host'),
            'driver' => 'pdo_mysql',
        );
        
        $dbh = \Doctrine\DBAL\DriverManager::getConnection($params, $config);

        return $dbh;
    }

}