<?php namespace Tavro\Command\Tavro;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class ClearCacheCommand extends ContainerAwareCommand
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
        $this->setName('tavro:cache:clear')
             ->setDescription('Clear the application cache and reset permissions.')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        $root = '/dev/shm/tavro';
        $cache = sprintf('%s/cache', $root);
        $logs = sprintf('%s/logs', $root);

        /**
         * Remove the entire cache/logs directory
         */
        $process = new Process(sprintf('sudo rm -rf %s', $root));
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        /**
         * Recreate the cache/logs home dir
         */
        $process = new Process(sprintf('sudo mkdir %s', $root));
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        /**
         * Reset permissions on cache/logs home
         */
        $process = new Process(sprintf('sudo chmod -R 0777 %s', $root));
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $output->writeln('');
        $output->writeln('<info> TAVRO: cache / logs rebuid complete</info>');
        $output->writeln('');

    }

    protected function waitInterval()
    {
        sleep($this->interval);
    }
}
