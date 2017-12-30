<?php namespace Tavro\Handler;

use Faker\Provider\cs_CZ\DateTime;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use Tavro\Entity\User;
use Tavro\Exception\Api\ApiAccessDeniedException;

class DateHandler implements ContainerAwareInterface
{
    private $container;
    private $timezone;

    const DEFAULT_TIMEZONE = 'UTC';

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function __construct($timezone)
    {
        $this->timezone = $timezone;
    }

    public function now()
    {
        $date = new \DateTime();
        $tz = new \DateTimeZone(self::DEFAULT_TIMEZONE);
        $date->setTimezone($tz);
    }

}