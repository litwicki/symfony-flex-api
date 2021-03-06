<?php

namespace Tests;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * The kernel class needs to be autoloaded in order for this to work.
 * @see https://github.com/symfony/symfony-standard/commit/ab358a4458b052fc80a3e99a93b125c1ef133a38
 */
trait SymfonyKernel
{
    /**
     * @var Kernel
     */
    protected $kernel;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @before
     */
    protected function setUpSymfonyKernel()
    {
        $this->kernel = $this->createKernel();
        $this->kernel->boot();

        $this->container = $this->kernel->getContainer();
    }

    /**
     * @param array $options
     *
     * @return Kernel
     */
    protected function createKernel()
    {
        $class = $this->getKernelClass();
        $options = $this->getKernelOptions();

        return new $class(
            isset($options['environment']) ? $options['environment'] : 'test',
            isset($options['debug']) ? $options['debug'] : true
        );
    }

    /**
     * @return string
     */
    protected function getKernelClass()
    {
        return \AppKernel::class;
    }

    /**
     * @return string
     */
    protected function getKernelOptions()
    {
        return ['environment' => 'test', 'debug' => true];
    }

    /**
     * @after
     */
    protected function tearDownSymfonyKernel()
    {
        if (null !== $this->kernel) {
            $this->kernel->shutdown();
        }
    }
}