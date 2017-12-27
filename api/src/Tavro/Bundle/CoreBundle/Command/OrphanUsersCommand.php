<?php

namespace Tavro\Bundle\CoreBundle\Command;
use PDO;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

use Tavro\Bundle\CoreBundle\Entity\User;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrphanUsersCommand extends ContainerAwareCommand
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
        $this->setName('tavro:users:orphans')
             ->setDescription('Find all Users who do not have a home :(')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

      $em = $this->getContainer()->get('doctrine')->getManager();

      /**
       * Find all Accounts whose trial ended before today
       */
        $query = $em->createQueryBuilder('TavroCoreBundle:User u')
            ->leftJoin('u.account_users', 'account_users')
            ->leftJoin('u.accounts', 'accounts')
            ->where('SIZE(u.account_users) = 0')
            ->andWhere('SIZE(u.accounts) = 0');

      $users = $query->getResult();

      if(!empty($users)) {

        $mailer = $this->getContainer()->get('tavro.mailer');
        $mailer->send([
            'type' => 'System/User/OrphanUsersReport',
            'recipients' => $mailer->getAppEmail(),
            'subject' => 'Tavro: Orphan Users',
            'users' => $users
        ]);

      }
      else {

        $mailer = $this->getContainer()->get('tavro.mailer');
        $mailer->send([
            'recipients' => $mailer->getAppEmail(),
            'subject' => 'Tavro: Orphan Users',
            'message' => 'There are no orphans in Tavro!'
        ]);

      }

    }

    protected function waitInterval()
    {
        sleep($this->interval);
    }
}
