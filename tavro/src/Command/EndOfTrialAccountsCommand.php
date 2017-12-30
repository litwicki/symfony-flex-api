<?php namespace Tavro\Command;

use PDO;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;

use Tavro\Entity\User;
use Tavro\Entity\Account;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EndOfTrialAccountsCommand extends ContainerAwareCommand
{

    private $trial_ending_days = 7;

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
        $this->setName('tavro:accounts:trials-ending')
            ->setDescription('Fetch all Accounts approaching the end of their trial period, and notify them, and the internal staff support team(s).')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        $now = new \DateTime();
        $trialEndDate = $now;
        $trialEndDate->add(new \DateInterval(sprintf('P%sD', $this->trial_ending_days)));

        $em = $this->getContainer()->get('doctrine')->getManager();

        /**
         * Find all Accounts whose trial ends in the next seven days.
         */
        $query = $em->createQuery('SELECT e FROM TavroCoreBundle:Account a WHERE a.trial_end_date <= :week_from_today');
        $query->setParameter('week_from_today', $trialEndDate->format('Y-m-d'));
        $accounts = $query->getResult();

        if(empty($accounts)) {

            /**
             * Send a simple notice to the internal support team.
             */
            $this->sendNoticeNoTrialAccounts();

        }
        else {

            foreach($accounts as $account) {
                $this->sendAccountNoticeTrialEnding($account);
            }

            /**
             * Send a Summary email to internal support team.
             */
            $this->sendSummaryTrialAccounts($accounts);

        }

    }

    /**
     * Send an email to the internal support team
     *
     * @throws \Exception
     */
    protected function sendNoticeNoTrialAccounts()
    {
        try {
            $mailer = $this->getContainer()->get('tavro_mailer');
            $mailer->send([
                'recipients' => $mailer->getAppEmail(),
                'subject' => 'Tavro Trial Accounts Audit Report',
                'message' => 'There are no Accounts with trial period ending the next seven days.'
            ]);
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Send the Account owner an email that their trial is ending.
     *
     * @param Account $account
     * @throws \Exception
     */
    protected function sendAccountNoticeTrialEnding(Account $account)
    {
        try {
            $mailer = $this->getContainer()->get('tavro_mailer');
            $mailer->send([
                'type' => 'System/Account/TrialAccountsEnding',
                'recipients' => $account->getUser()->getPerson()->getEmail(),
                'subject' => 'Your Tavro Trial Account is Expiring Soon',
                'account' => $account
            ]);
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Send an email to the internal support team
     *
     * @param array $accounts
     *
     * @throws \Exception
     */
    protected function sendSummaryTrialAccounts(array $accounts)
    {
        try {
            $mailer = $this->getContainer()->get('tavro_mailer');
            $mailer->send([
                'type' => 'System/Account/SummaryTrialAccountsEnding',
                'recipients' => $mailer->getAppEmail(),
                'subject' => 'Tavro Trial Accounts Audit Report',
                'accounts' => $accounts
            ]);
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    protected function waitInterval()
    {
        sleep($this->interval);
    }
}
