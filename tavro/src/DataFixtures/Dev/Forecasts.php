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
use Tavro\Entity\Forecast;
use Tavro\Entity\ForecastExpense;
use Tavro\Entity\ForecastRevenue;
use Tavro\Entity\ForecastStaffPerson;

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
