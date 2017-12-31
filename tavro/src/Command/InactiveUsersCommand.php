<?php

namespace App\Command;
use PDO;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

use App\Entity\User;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InactiveUsersCommand extends ContainerAwareCommand
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
        $this->setName('tavro:users:inactive')
             ->setDescription('Find all Users who have not logged in for the last 30 days, but ignore those inactive more than 90 days.')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

      $em = $this->getContainer()->get('doctrine')->getManager();
      $today = new \DateTime();
      $today->setTimezone(new \DateTimeZone($this->getContainer()->getParameter('timezone')));

      $thirtyDays = $today;
      $thirtyDays->sub(new \DateInterval('P30D'));

      $ninetyDays = $today;
      $ninetyDays->sub(new \DateInterval('P90D'));

      /**
       * Find all Accounts whose trial ended before today
       */
      $query = $em->createQuery('SELECT u FROM TavroCoreBundle:User u WHERE u.last_online_date < :thirty_days AND u.last_online_date >= :ninety_days');
      $query->setParameter('thirty_days', $thirtyDays->format('Y-m-d'));
      $query->setParameter('ninety_days', $ninetyDays->format('Y-m-d'));
      $users = $query->getResult();

      if(!empty($users)) {

        $mailer = $this->getContainer()->get('tavro.mailer');
        $mailer->send([
            'type' => 'System/User/TavroInactiveUsersReport',
            'recipients' => $mailer->getAppEmail(),
            'subject' => 'Tavro: Inactive Users',
            'users' => $users
        ]);

      }
      else {

        $mailer = $this->getContainer()->get('tavro.mailer');
        $mailer->send([
            'recipients' => $mailer->getAppEmail(),
            'subject' => 'Tavro: Inactive Users',
            'message' => 'There are no inactive Users to look into for this period.'
        ]);

      }

    }

    protected function waitInterval()
    {
        sleep($this->interval);
    }
}
