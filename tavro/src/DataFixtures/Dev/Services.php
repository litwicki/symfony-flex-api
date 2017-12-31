<?php

namespace App\DataFixtures\Dev;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Role;
use App\Entity\Variable;
use App\Entity\Organization;
use App\Entity\Shareholder;
use App\Entity\Product;
use App\Entity\Service;
use App\Entity\Expense;
use App\Entity\Node;
use App\Entity\Revenue;
use App\Entity\Tag;
use App\Entity\AccountUser;
use App\Entity\ExpenseCategory;
use App\Entity\ExpenseComment;
use App\Entity\ExpenseTag;
use App\Entity\FundingRound;
use App\Entity\FundingRoundComment;
use App\Entity\NodeComment;
use App\Entity\ProductCategory;
use App\Entity\RevenueCategory;
use App\Entity\ServiceCategory;
use App\Entity\Contact;
use App\Entity\OrganizationComment;
use App\Entity\FundingRoundShareholder;
use App\Entity\RevenueService;
use App\Entity\RevenueProduct;

use Cocur\Slugify\Slugify;


/**
 * Defines all predefined installed types created during install.
 *
 * @author jake.litwicki@gmail.com
 */
class Services extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $faker = \Faker\Factory::create('en_EN');
        $size = 10;

        $accounts = $manager->getRepository('TavroCoreBundle:Account')->findAll();

        $types = array('hourly', 'unit', 'retainer');

        foreach($accounts as $account) {

            $serviceCategories = $account->getServiceCategories()->toArray();

            $services = array();

            for($i=0;$i<$size;$i++) {

                $service = new Service();
                $service->setAccount($account);
                $service->setCreateDate(new \DateTime());
                $service->setCategory($serviceCategories[array_rand($serviceCategories)]);
                $service->setName(ucwords($faker->text(rand(10,100))));
                $service->setBody($faker->text(rand(100,1000)));
                $service->setStatus(rand(0,1));
                $service->setPrice(rand(1.00, 999.99));
                $service->setType($types[array_rand($types)]);
                $manager->persist($service);
                $services[] = $service;
            }

            $manager->flush();

        }

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 12; // the order in which fixtures will be loaded
    }
}
