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
