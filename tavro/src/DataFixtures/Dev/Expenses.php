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

use Cocur\Slugify\Slugify;


/**
 * Defines all predefined installed types created during install.
 *
 * @author jake.litwicki@gmail.com
 */
class Expenses extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $expenses = array();

        $accounts = $manager->getRepository('TavroCoreBundle:Account')->findAll();
        $tags = $manager->getRepository('TavroCoreBundle:Tag')->findAll();

        foreach($accounts as $account) {

            $expenseCategories = $account->getExpenseCategories()->toArray();
            $users = $account->getUsers()->toArray();

            $expenses = array();

            foreach($expenseCategories as $category) {

                for($i=0;$i<rand(1,$size);$i++) {

                    $date = new \DateTime();
                    $expense = new Expense();
                    $expense->setAccount($account);
                    $expense->setCategory($category);
                    $expense->setBody($faker->text(rand(100,1000)));
                    $expense->setCreateDate(new \DateTime());
                    $expense->setStatus(rand(0,1));
                    $expense->setAmount(rand(0,9999));
                    $expense->setExpenseDate($date->add(\DateInterval::createFromDateString(sprintf('-%s days', rand(1,90)))));
                    $expense->setCategory($category);
                    $expense->setUser($users[array_rand($users)]);
                    $manager->persist($expense);
                    $expenses[] = $expense;

                }

            }

            $manager->flush();

        }

        foreach($expenses as $expense) {

            for($i=0;$i<rand(0,$size);$i++) {
                $comment = new Comment();
                $comment->setUser($users[array_rand($users)]);
                $comment->setBody($faker->text(rand(100,1000)));
                $comment->setStatus(rand(0,1));
                $manager->persist($comment);
                $manager->flush();

                $ec = new ExpenseComment();
                $ec->setExpense($expense);
                $ec->setComment($comment);
                $manager->persist($ec);
                $manager->flush();
            }

            /**
             * For this Expense, let's add some tags..
             */
            for($i=0;$i<rand(0,$size);$i++) {
                $expenseTag = new ExpenseTag();
                $expenseTag->setExpense($expense);
                $expenseTag->setTag($tags[array_rand($tags)]);
                $manager->persist($expenseTag);
            }

            $manager->flush();

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
