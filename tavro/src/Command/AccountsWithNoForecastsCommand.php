<?php

namespace Tavro\Command;
use PDO;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

use Tavro\Entity\User;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AccountsWithNoForecastsCommand extends ContainerAwareCommand
{
    protected $interval = 5;
    /**
     * @var array
     */
    protected $config = array();

    /**
     * @var array
     */
    protected $connections = array();

    protected function configure()
    {
        $this->setName('tavro:accounts:no-forecasts')
             ->setDescription('Find Accounts without a single Forecast, for a marketing drip.')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

      $em = $this->getContainer()->get('doctrine')->getManager();

      /**
       * Find all Accounts that are at least one week old and have not created a forecast
       */
      $query = $em->createQueryBuilder('TavroCoreBundle:Account a')
        ->leftJoin('a.forecasts', 'forecasts')
        ->where('SIZE(a.forecasts) = 0')
        ->andWhere('a.create_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)');

      $accounts = $query->getResult();

      if(!empty($accounts)) {
        foreach($accounts as $account) {

          $mailer = $this->getContainer()->get('tavro_mailer');
          $mailer->send([
              'type' => 'Account/AccountNoForecastsAudit',
              'recipients' => $mailer->getAppEmail(),
              'subject' => 'Tavro Account Forecasting',
              'account' => $account
          ]);

        }
      }

    }

    protected function waitInterval()
    {
        sleep($this->interval);
    }
}
