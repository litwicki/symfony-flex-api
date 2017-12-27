<?php

namespace Tavro\Bundle\CoreBundle\DataFixtures\Dev;

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
