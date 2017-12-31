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
