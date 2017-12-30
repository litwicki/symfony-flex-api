<?php

namespace Tavro\DataFixtures\Core\v1;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Tavro\Entity\User;
use Tavro\Entity\Role;
use Tavro\Entity\Variable;
use Tavro\Entity\AccountType;

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
            'ROLE_INVESTOR'     => 'Investor',
            'ROLE_ACCOUNTANT'   => 'Accountant',
            'ROLE_TAVRO'        => 'Staff',
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

        /**
         * Create the default AccountTypes
         */

        $types = array(
            'Manufacturing' => 'Unlike a merchandising business, a manufacturing business buys products with the intention of using them as materials in making a new product. Thus, there is a transformation of the products purchased. A manufacturing business combines raw materials, labor, and factory overhead in its production process. The manufactured goods will then be sold to customers.',
            'Merchandising' => 'This type of business buys products at wholesale price and sells the same at retail price. They are known as "buy and sell" businesses. They make profit by selling the products at prices higher than their purchase costs. A merchandising business sells a product without changing its form. Examples are: grocery stores, convenience stores, distributors, and other resellers.',
            'Service' => 'A service type of business provides intangible products (products with no physical form). Service type firms offer professional skills, expertise, advice, and other similar products. Examples of service businesses are: schools, repair shops, hair salons, banks, accounting firms, and law firms.',
            'Software as a Service (SaaS)' => 'Software as a service (SaaS) is a software licensing and delivery model in which software is licensed on a subscription basis and is centrally hosted. It is sometimes referred to as "on-demand software", and was formerly referred to as "software plus services" by Microsoft. SaaS is typically accessed by users using a thin client via a web browser.'
        );

        foreach($types as $name => $body) {
            $type = new AccountType();
            $type->setBody($body);
            $type->setName($name);
            $manager->persist($type);
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
