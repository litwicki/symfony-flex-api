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

class ImageCacheCommand extends ContainerAwareCommand
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
        $this->setName('tavro:core:imagecache')
             ->setDescription('Update the image cache for ModImages.')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        try {

            /**
             * For each Image we have on file, apply the cache for every filter we have.
             * app/console liip:imagine:cache:resolve relative/path/to/image.jpg relative/path/to/image2.jpg --filters=my_thumb
             */

            $handler = $this->get('tavro.handler.mod_images');
            $modImages = $handler->findAll();

            if(!empty($modImages)) {

                //$cacheManager = $this->get('liip_imagine.cache.manager');

                foreach($modImages as $modImage) {

                    $image = $modImage->getImage();

                    $filters = array(
                        'mod_thumbnail',
                        'tiny_mod'
                    );

                    foreach($filters as $filter) {

                        $cmd = sprintf('php /var/www/tavro/www/app/console liip:imagine:cache:resolve %s --filters=%s',
                            $image->getAwsUrl(),
                            $filter
                        );

                        $process = new Process($cmd);
                        $process->run();

                        // executes after the command finishes
                        if (!$process->isSuccessful()) {
                            throw new \RuntimeException($process->getErrorOutput());
                        }

                        echo $process->getOutput();

                    }

                }

            }

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
