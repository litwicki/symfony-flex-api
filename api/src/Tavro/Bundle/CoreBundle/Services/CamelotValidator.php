<?php

namespace Tavro\Bundle\CoreBundle\Services;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Tavro\Bundle\CoreBundle\Exception\Password\PasswordComplexityException;
use Tavro\Bundle\CoreBundle\Exception\Password\PasswordLengthException;
use Tavro\Bundle\CoreBundle\Exception\Password\PasswordInvalidCharacterException;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TavroValidator implements ContainerAwareInterface
{
    public function __construct()
    {
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Validates a single email address (or an array of email addresses)
     *
     * @param array|string $item
     *
     * @return array
     */
    public function emails($item)
    {

        $errors = array();
        $emails = is_array($item) ? $item : array($item);

        foreach($emails as $email) {

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = sprintf('%s is not a valid email address', $email);
            }

        }

        return $errors;
    }

    /**
     * @param $password
     *
     * @return bool
     * @throws \Exception
     */
    public function passwordComplexity($password)
    {

        $specials = '/[!@#$%^&*()\-_=+]/';  // whatever you mean by 'special char'
        $numbers = '/[0-9]/';  //numbers
        $errors = array();

        if (preg_match_all($specials, $password, $o) < 1) {
            $errors[] = sprintf(
                'Password "%s" does not contain at least one special character: %s',
                $password,
                str_replace('/', '', $specials)
            );
        }

        if (preg_match_all($numbers, $password, $o) < 1) {
            $errors[] = sprintf(
                'Password "%s" does not contain a number.',
                $password
            );
        }

        if (strlen($password) < 8) {
            $errors[] = sprintf('Your password must be at least 8 characters long.');
        }

        if (preg_match("/\\s/", $password)) {
            $errors[] = sprintf('Your password cannot contain spaces.');
        }

        if(empty($errors)) {
            return true;
        }
        else {
            throw new \Exception(implode('<br>', $errors));
        }

    }

}