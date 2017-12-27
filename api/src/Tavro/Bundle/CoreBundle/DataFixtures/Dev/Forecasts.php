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
use Tavro\Bundle\CoreBundle\Entity\Forecast;
use Tavro\Bundle\CoreBundle\Entity\ForecastExpense;
use Tavro\Bundle\CoreBundle\Entity\ForecastRevenue;
use Tavro\Bundle\CoreBundle\Entity\ForecastStaffPerson;

use Cocur\Slugify\Slugify;


/**
 * Defines all predefined installed types created during install.
 *
 * @author jake.litwicki@gmail.com
 */
class Forecasts extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $accounts = $manager->getRepository('TavroCoreBundle:Account')->findAll();

        foreach($accounts as $account) {

            $forecasts = array();
            $expenseCategories = $account->getExpenseCategories()->toArray();
            $revenueCategories = $account->getRevenueCategories()->toArray();
            $users = $account->getUsers()->toArray();

            for($i=0;$i<(rand(1,10));$i++) {

                $forecast = new Forecast();
                $forecast->setUser($users[array_rand($users)]);
                $forecast->setBody($faker->text(rand(100,1000)));
                $forecast->setAccount($account);
                $forecast->setTitle($faker->text(rand(100,255)));
                $manager->persist($forecast);
                $manager->flush();
                $forecasts[] = $forecast;
            }

            foreach($forecasts as $forecast) {

                /**
                 * Create some Expenses
                 */
                for($i=0;$i<(rand(1,10));$i++) {
                    $e = new ForecastExpense();
                    $e->setAmount(rand(1,1000));
                    $e->setUser($users[array_rand($users)]);
                    $e->setBody($faker->text(rand(100,1000)));
                    $e->setCategory($expenseCategories[array_rand($expenseCategories)]);
                    $e->setExpenseDate(new \DateTime($faker->dateTimeThisCentury->format('Y-m-d')));
                    $e->setForecast($forecast);
                    $manager->persist($e);
                }

                /**
                 * Create some Revenues
                 */
                for($i=0;$i<(rand(1,10));$i++) {
                    $e = new ForecastRevenue();
                    $e->setUser($users[array_rand($users)]);
                    $e->setBody($faker->text(rand(100,1000)));
                    $e->setCategory($revenueCategories[array_rand($revenueCategories)]);
                    $e->setRevenueDate(new \DateTime($faker->dateTimeThisCentury->format('Y-m-d')));
                    $e->setUnitAmount(rand(1,10000));
                    $e->setQty(rand(1,1000));
                    $e->setForecast($forecast);
                    $manager->persist($e);
                }

                /**
                 * Create some Staff
                 */
                for($i=0;$i<(rand(1,10));$i++) {

                    $startingSalary = rand(30000,100000);
                    $salary = $startingSalary % 2 === 0 ? ($startingSalary * 1.05) : $startingSalary;

                    $e = new ForecastStaffPerson();
                    $e->setForecast($forecast);
                    $e->setBody($faker->text(rand(100,1000)));
                    $e->setJobTitle($faker->jobTitle);
                    $e->setStartingSalary($startingSalary);
                    $e->setCurrentSalary($salary);
                    $e->setAccount($account);
                    $e->setHireDate(new \DateTime($faker->dateTimeThisCentury->format('Y-m-d')));
                    $e->setStartDate(new \DateTime($faker->dateTimeThisCentury->format('Y-m-d')));
                    $manager->persist($e);
                }

                $manager->flush();

            }

        }

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 10; // the order in which fixtures will be loaded
    }

}
