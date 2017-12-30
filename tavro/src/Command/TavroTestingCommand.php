<?php

namespace Tavro\Command;
use PDO;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class TavroTestingCommand extends ContainerAwareCommand
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
        $this->setName('tavro:testing')
             ->setDescription('Execute all Unit Tests for Symfony using PHPUnit')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $process = new Process('cd /var/www/tavro/api && phpunit --coverage-html /var/www/tavro/phpunit');

        $process->run(function ($type, $buffer) {
            if (Process::ERR === $type) {
                echo 'ERROR: ' . $buffer;
            } else {
                echo $buffer;
            }
        });
    }

    protected function waitInterval()
    {
        sleep($this->interval);
    }
}
