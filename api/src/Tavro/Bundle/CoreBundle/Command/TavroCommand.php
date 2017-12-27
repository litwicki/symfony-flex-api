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

class TavroCommand extends ContainerAwareCommand
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
        $this->setName('tavro:core:COMMAND_NAME')
             ->setDescription('')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

    }

    protected function waitInterval()
    {
        sleep($this->interval);
    }
}
