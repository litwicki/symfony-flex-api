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
use Tavro\Entity\RevenueService;
use Tavro\Entity\RevenueProduct;

use Cocur\Slugify\Slugify;


/**
 * Defines all predefined installed types created during install.
 *
 * @author jake.litwicki@gmail.com
 */
class Tags extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $size = 25;
        $faker = \Faker\Factory::create('en_EN');

        $tags = array();

        $accounts = $manager->getRepository('TavroCoreBundle:Account')->findAll();

        foreach($accounts as $account) {

            for($i=0;$i<rand(5,$size);$i++) {

                $tag = new Tag();
                $tag->setAccount($account);
                $tag->setTag($faker->text(rand(5,30)));
                $tag->setBody($faker->text(rand(100,1000)));
                $tag->setStatus(1);
                $manager->persist($tag);
                $tags[] = $tag;

            }

        }

        $manager->flush();

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 8; // the order in which fixtures will be loaded
    }

}