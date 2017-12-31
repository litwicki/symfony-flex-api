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

use Cocur\Slugify\Slugify;


/**
 * Defines all predefined installed types created during install.
 *
 * @author jake.litwicki@gmail.com
 */
class Products extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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

        foreach($accounts as $account) {

            $productCategories = $account->getProductCategories()->toArray();

            for($i=0;$i<rand(0,$size);$i++) {

                $category = $productCategories[array_rand($productCategories)];
                $cost = rand(0.99, 999.99);
                $product = new Product();
                $product->setCategory($category);
                $product->setCreateDate(new \DateTime());
                $product->setStatus(rand(0,1));
                $product->setName($faker->text(rand(10,100)));
                $product->setBody($faker->text(rand(100,1000)));
                $product->setAccount($account);
                $product->setCost($cost);
                $product->setPrice($cost * 1.15);
                $manager->persist($product);

            }

            $manager->flush();

        }

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 11; // the order in which fixtures will be loaded
    }

}
