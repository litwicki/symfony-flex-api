<?php

namespace Tavro\Bundle\ApiBundle\DataFixtures\Demo;

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
use Tavro\Bundle\CoreBundle\Entity\OrganizationShareholder;
use Tavro\Bundle\CoreBundle\Entity\Shareholder;
use Tavro\Bundle\CoreBundle\Entity\Product;
use Tavro\Bundle\CoreBundle\Entity\Service;
use Tavro\Bundle\CoreBundle\Entity\Expense;
use Tavro\Bundle\CoreBundle\Entity\Node;
use Tavro\Bundle\CoreBundle\Entity\Revenue;
use Tavro\Bundle\CoreBundle\Entity\Tag;
use Tavro\Bundle\CoreBundle\Entity\UserOrganization;
use Tavro\Bundle\CoreBundle\Entity\ExpenseCategory;
use Tavro\Bundle\CoreBundle\Entity\ExpenseComment;
use Tavro\Bundle\CoreBundle\Entity\ExpenseTag;
use Tavro\Bundle\CoreBundle\Entity\FundingRound;
use Tavro\Bundle\CoreBundle\Entity\FundingRoundComment;
use Tavro\Bundle\CoreBundle\Entity\NodeComment;
use Tavro\Bundle\CoreBundle\Entity\ProductCategory;
use Tavro\Bundle\CoreBundle\Entity\RevenueCategory;
use Tavro\Bundle\CoreBundle\Entity\ServiceCategory;
use Tavro\Bundle\CoreBundle\Entity\Customer;
use Tavro\Bundle\CoreBundle\Entity\CustomerComment;
use Tavro\Bundle\CoreBundle\Entity\FundingRoundShareholder;

use Cocur\Slugify\Slugify;
use Litwicki\Common\Common as Litwicki;

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

    public function getCities($state)
    {
        $json = file_get_contents(sprintf('http://api.sba.gov/geodata/city_links_for_state_of/%s.json', $state));
        $data = json_decode($json, true);
        $cities = array();
        foreach($data as $item) {
            $cities[] = $item['name'];
        }
        return $cities;
    }

    public function getStates()
    {
        return Litwicki::getStateSelectChoices();
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $lipsum = $this->container->get('apoutchika.lorem_ipsum');
        $size = 10;

        $organizations = $manager->getRepository('TavroCoreBundle:Organization')->findAll();
        $users = $manager->getRepository('TavroCoreBundle:User')->findAll();
        $tags = $manager->getRepository('TavroCoreBundle:Tag')->findAll();
        $expenseCategories = $manager->getRepository('TavroCoreBundle:ExpenseCategory')->findAll();

        foreach($organizations as $organization) {

            $expenses = array();

            for($i=0;$i<$size;$i++) {

                $expenseDate = new \DateTime();
                $category = $expenseCategories[array_rand($expenseCategories)];

                $expense = new Expense();
                $expense->setOrganization($organization);
                $expense->setCategory($category);
                $expense->setCreateDate(new \DateTime());
                $expense->setStatus(rand(0,1));
                $expense->setAmount(rand(0,9999));
                $expense->setExpenseDate($expenseDate->add(\DateInterval::createFromDateString(sprintf('-%s days', rand(1,90)))));
                $expense->setCategory($category);
                $expense->setUser($users[array_rand($users)]);
                $manager->persist($expense);
                $expenses[] = $expense;

            }

            $manager->flush();

            foreach($expenses as $expense) {

                for($i=0;$i<rand(0,$size);$i++) {
                    $comment = new Comment();
                    $comment->setUser($users[array_rand($users)]);
                    $comment->setBody($lipsum->getSentences(rand(1,10)));
                    $comment->setStatus(rand(0,1));
                    $comment->setTitle($lipsum->getWords(rand(2,10)));
                    $manager->persist($comment);
                    $manager->flush();

                    $ec = new ExpenseComment();
                    $ec->setExpense($expense);
                    $ec->setComment($comment);
                    $manager->persist($ec);
                    $manager->flush();
                }

                for($i=0;$i<rand(0,$size);$i++) {
                    $expenseTag = new ExpenseTag();
                    $expenseTag->setExpense($expense);
                    $expenseTag->setTag($tags[array_rand($tags)]);
                    $manager->persist($expenseTag);
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