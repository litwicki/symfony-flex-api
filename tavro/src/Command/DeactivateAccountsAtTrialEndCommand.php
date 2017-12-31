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

class DeactivateAccountsAtTrialEndCommand extends ContainerAwareCommand
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
        $this->setName('tavro:accounts:deactivate-trial-ended')
            ->setDescription('Deactivate any accounts that have expired their trial period.')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        $today = new \DateTime();
        $today->setTimezone(new \DateTimeZone($this->getContainer()->getParameter('timezone')));

        $em = $this->getContainer()->get('doctrine')->getManager();

        /**
         * Find all Accounts whose trial ended before today
         */
        $query = $em->createQuery('SELECT e FROM TavroCoreBundle:Account a WHERE a.trial_end_date < :today');
        $query->setParameter('today', $today->format('Y-m-d'));
        $accounts = $query->getResult();

        if(!empty($accounts)) {

            $accountHandler = $this->getContainer()->get('tavro.handler.accounts');

            foreach($accounts as $account) {

                $accountHandler->deactivate($account);

            }

            $mailer = $this->getContainer()->get('tavro_mailer');
            $mailer->send([
                'type' => 'System/Account/SummaryDeactivatedTrialAccounts',
                'recipients' => $mailer->getAppEmail(),
                'subject' => 'Tavro Trial Accounts Deactivated',
                'accounts' => $accounts
            ]);

        }
        else {

            $mailer = $this->getContainer()->get('tavro_mailer');
            $mailer->send([
                'recipients' => $mailer->getAppEmail(),
                'subject' => 'Tavro Trial Accounts Deactivated',
                'message' => 'There were 0 Accounts deactivated because of an expired trial period.'
            ]);

        }

    }

    protected function waitInterval()
    {
        sleep($this->interval);
    }
}
