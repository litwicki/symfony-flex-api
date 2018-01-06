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
