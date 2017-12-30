<?php

namespace Tavro\DataFixtures\Dev;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Tavro\Entity\Comment;
use Tavro\Entity\User;
use Tavro\Entity\Role;
use Tavro\Entity\Variable;
use Tavro\Entity\Organization;
use Tavro\Entity\Shareholder;
use Tavro\Entity\Product;
use Tavro\Entity\Service;
use Tavro\Entity\Expense;
use Tavro\Entity\Node;
use Tavro\Entity\Revenue;
use Tavro\Entity\Tag;
use Tavro\Entity\AccountUser;
use Tavro\Entity\ExpenseCategory;
use Tavro\Entity\ExpenseComment;
use Tavro\Entity\ExpenseTag;
use Tavro\Entity\FundingRound;
use Tavro\Entity\FundingRoundComment;
use Tavro\Entity\NodeComment;
use Tavro\Entity\ProductCategory;
use Tavro\Entity\RevenueCategory;
use Tavro\Entity\ServiceCategory;
use Tavro\Entity\Contact;
use Tavro\Entity\OrganizationComment;
use Tavro\Entity\FundingRoundShareholder;
use Tavro\Entity\RevenueService;
use Tavro\Entity\RevenueProduct;

use Cocur\Slugify\Slugify;


/**
 * Defines all predefined installed types created during install.
 *
 * @author jake.litwicki@gmail.com
 */
class Revenues extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $revenues = array();

        foreach($accounts as $account) {

            $revenueCategories = $account->getRevenueCategories()->toArray();
            $users = $account->getUsers()->toArray();
            $services = $account->getServices()->toArray();
            $products = $account->getProducts()->toArray();

            $revenues = array();

            foreach($account->getOrganizations() as $organization) {

                foreach($revenueCategories as $category) {

                    for($i=0;$i<$size;$i++) {

                        $revenue = new Revenue();
                        $revenue->setOrganization($organization);
                        $revenue->setStatus(rand(0,1));
                        $revenue->setCreateDate(new \DateTime());
                        $revenue->setUser($users[array_rand($users)]);
                        $revenue->setBody($faker->text(rand(100,1000)));
                        $revenue->setCategory($category);
                        $revenue->setAccount($account);
                        $manager->persist($revenue);
                        $revenues[] = $revenue;

                    }

                    $manager->flush();

                }

            }

        }

        foreach($revenues as $revenue) {

            for($i=0;$i<rand(1,$size);$i++) {

                // alternate even/odd for product/service to be equally generated
                if($i % 2 == 0) {
                    if(count($services)) {
                        $entity = new RevenueService();
                        $entity->setService($services[array_rand($services)]);
                        $entity->setRevenue($revenue);
                        $manager->persist($entity);
                    }
                }
                else {
                    if(count($products)) {
                        $entity = new RevenueProduct();
                        $entity->setProduct($products[array_rand($products)]);
                        $entity->setRevenue($revenue);
                        $manager->persist($entity);
                    }
                }

            }

            $manager->flush();

        }

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 13; // the order in which fixtures will be loaded
    }
}
