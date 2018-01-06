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
        $users = $manager->getRepository('TavroCoreBundle:User')->findAll();

        $faker = \Faker\Factory::create('en_EN');

        foreach($accounts as $account) {

            $expenseCategories = array();
            $revenueCategories = array();
            $serviceCategories = array();
            $productCategories = array();

            $items = [
                'Software',
                'Parts & Accessories',
                'Hardware',
                'Physical Goods',
                'Perishable'
            ];

            foreach($items as $name) {
                $productCategory = new ProductCategory();
                $productCategory->setAccount($account);
                $productCategory->setCreateDate(new \DateTime());
                $productCategory->setStatus(1);
                $productCategory->setBody($name);
                $manager->persist($productCategory);
                $productCategories[] = $productCategory;
            }

            $manager->flush();

            $items = [
                'Premium Support',
                'Consulting',
                'Training',
                'Integrations'
            ];

            foreach($items as $name) {
                $serviceCategory = new ServiceCategory();
                $serviceCategory->setAccount($account);
                $serviceCategory->setCreateDate(new \DateTime());
                $serviceCategory->setStatus(1);
                $serviceCategory->setBody($name);
                $manager->persist($serviceCategory);
                $serviceCategories[] = $serviceCategory;
            }

            $manager->flush();

            $items = [
                'Sales',
                'Subscriptions',
                'Consulting',
                'Other Services'
            ];

            foreach($items as $name) {
                $revenueCategory = new RevenueCategory();
                $revenueCategory->setAccount($account);
                $revenueCategory->setCreateDate(new \DateTime());
                $revenueCategory->setStatus(1);
                $revenueCategory->setBody($name);
                $manager->persist($revenueCategory);
                $revenueCategories[] = $revenueCategory;
            }

            $manager->flush();

            $items = [
                'Supplies',
                'Marketing',
                'Software',
                'Hardware',
                'Subscriptions',
                'Other'
            ];

            foreach($items as $name) {
                $expenseCategory = new ExpenseCategory();
                $expenseCategory->setAccount($account);
                $expenseCategory->setCreateDate(new \DateTime());
                $expenseCategory->setStatus(1);
                $expenseCategory->setBody($name);
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
        return 6; // the order in which fixtures will be loaded
    }

}
