<?php

namespace Tavro\Bundle\CoreBundle\Security\Authentication\Provider;

use Doctrine\ORM\NoResultException;
use Doctrine\ORM\EntityManager;

use Symfony\Component\Security\Core\User\UserProviderInterface;
//use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

use Tavro\Bundle\CoreBundle\Entity\User;

class UserProvider implements UserProviderInterface
{
    private $em;
    private $repository;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repository = $this->em->getRepository('TavroCoreBundle:User');
    }

    /**
     *  Load the Username (Email) of a User by API Key
     *
     * @param $key_raw
     *
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     * @internal param string $api_key User.api_key (decrypted)
     *
     * @return mixed $email string
     */
    public function getUsernameForApiKey($key_raw)
    {
        try {

            $message = sprintf('Unable to find an active user with key %s.', $key_raw);
            $api_key = User::staticEncrypt($key_raw);
            $user = $this->repository->findOneBy(array('api_key' => $api_key));

            if(!is_object($user)) {
                throw new UsernameNotFoundException($message);
            }

            return $user->getUsername();

        }
        catch (NoResultException $e) {
            throw new UsernameNotFoundException($e->getMessage(), 0, $e);
        }

    }

    /**
     * @param string $username
     *
     * @return null|object
     * @throws \Exception
     */
    public function loadUserByUsername($username)
    {
        try {

            $user = $this->repository->findOneBy(array('username' => $username));

            if(!$user instanceof User) {
                $user = $this->repository->findByEmail($username);
            }

            if(!$user instanceof User) {
                $message = sprintf('Error loading user with email or username of %s', $username);
                throw new UsernameNotFoundException($message);
            }

            return $user;

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return null|object
     */
    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
        }

        return $this->repository->find($user->getId());
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return 'Tavro\Bundle\CoreBundle\Entity\User' === $class;
    }

}
