<?php

namespace Tavro\Bundle\CoreBundle\DataFixtures\Core\v1;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Entity\Role;
use Tavro\Bundle\CoreBundle\Entity\Variable;

use Cocur\Slugify\Slugify;

/**
 * Defines all predefined installed types created during install.
 *
 * @author jake.litwicki@gmail.com
 */
class Core extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

        $roles = array(
            'ROLE_USER'         => 'User',
            'ROLE_DEVELOPER'    => 'Developer',
            'ROLE_ADMIN'        => 'Administrator',
            'ROLE_SUPERUSER'    => 'Superuser',
        );

        /**
         * Create Roles
         */
        foreach($roles as $role => $name) {
            $entity = new Role();
            $entity->setRole($role);
            $entity->setName($name);
            $manager->persist($entity);
        }

        $manager->flush();

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }

}
