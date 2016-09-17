<?php

namespace Tavro\Bundle\CoreBundle\DataFixtures\Demo;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Tavro\Bundle\CoreBundle\Entity\Comment;
use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Entity\Role;
use Tavro\Bundle\CoreBundle\Entity\Variable;
use Tavro\Bundle\CoreBundle\Entity\Organization;
use Tavro\Bundle\CoreBundle\Entity\Shareholder;
use Tavro\Bundle\CoreBundle\Entity\Product;
use Tavro\Bundle\CoreBundle\Entity\Service;
use Tavro\Bundle\CoreBundle\Entity\Expense;
use Tavro\Bundle\CoreBundle\Entity\Node;
use Tavro\Bundle\CoreBundle\Entity\Revenue;
use Tavro\Bundle\CoreBundle\Entity\Tag;
use Tavro\Bundle\CoreBundle\Entity\AccountUser;
use Tavro\Bundle\CoreBundle\Entity\ExpenseCategory;
use Tavro\Bundle\CoreBundle\Entity\ExpenseComment;
use Tavro\Bundle\CoreBundle\Entity\ExpenseTag;
use Tavro\Bundle\CoreBundle\Entity\FundingRound;
use Tavro\Bundle\CoreBundle\Entity\FundingRoundComment;
use Tavro\Bundle\CoreBundle\Entity\NodeComment;
use Tavro\Bundle\CoreBundle\Entity\ProductCategory;
use Tavro\Bundle\CoreBundle\Entity\RevenueCategory;
use Tavro\Bundle\CoreBundle\Entity\ServiceCategory;
use Tavro\Bundle\CoreBundle\Entity\Contact;
use Tavro\Bundle\CoreBundle\Entity\OrganizationComment;
use Tavro\Bundle\CoreBundle\Entity\FundingRoundShareholder;

use Cocur\Slugify\Slugify;
use Litwicki\Common\Common as Litwicki;

/**
 * Defines all predefined installed types created during install.
 *
 * @author jake.litwicki@gmail.com
 */
class Categories extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $accounts = $manager->getRepository('TavroCoreBundle:Account')->findAll();
        $users = $manager->getRepository('TavroCoreBundle:User')->findAllNonAdmin();

        $faker = \Faker\Factory::create('en_EN');

        foreach($accounts as $account) {

            $expenseCategories = array();
            $revenueCategories = array();
            $serviceCategories = array();
            $productCategories = array();

            for($i=0;$i<5;$i++) {
                $productCategory = new ProductCategory();
                $productCategory->setAccount($account);
                $productCategory->setCreateDate(new \DateTime());
                $productCategory->setStatus(1);
                $productCategory->setBody($faker->text(rand(10,500)));
                $manager->persist($productCategory);
                $productCategories[] = $productCategory;
            }

            $manager->flush();

            for($i=0;$i<5;$i++) {
                $serviceCategory = new ServiceCategory();
                $serviceCategory->setAccount($account);
                $serviceCategory->setCreateDate(new \DateTime());
                $serviceCategory->setStatus(1);
                $serviceCategory->setBody($faker->text(rand(10,500)));
                $manager->persist($serviceCategory);
                $serviceCategories[] = $serviceCategory;
            }

            $manager->flush();

            for($i=0;$i<5;$i++) {
                $revenueCategory = new RevenueCategory();
                $revenueCategory->setAccount($account);
                $revenueCategory->setCreateDate(new \DateTime());
                $revenueCategory->setStatus(1);
                $revenueCategory->setBody($faker->text(rand(10,500)));
                $manager->persist($revenueCategory);
                $revenueCategories[] = $revenueCategory;
            }

            $manager->flush();

            for($i=0;$i<5;$i++) {
                $expenseCategory = new ExpenseCategory();
                $expenseCategory->setAccount($account);
                $expenseCategory->setCreateDate(new \DateTime());
                $expenseCategory->setStatus(1);
                $expenseCategory->setBody($faker->text(rand(10,500)));
                $manager->persist($expenseCategory);
                $expenseCategories[] = $expenseCategory;
            }

            $manager->flush();

        }

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 4; // the order in which fixtures will be loaded
    }

}
