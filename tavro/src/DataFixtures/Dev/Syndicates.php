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
use Tavro\Entity\NodeTag;
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
use Tavro\Entity\Syndicate;

use Cocur\Slugify\Slugify;
use Litwicki\Common\Common as Litwicki;


/**
 * Defines all predefined installed types created during install.
 *
 * @author jake.litwicki@gmail.com
 */
class Syndicates extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        return;
        
        $faker = \Faker\Factory::create('en_EN');
        $repo = $manager->getRepository('TavroCoreBundle:User');

        for($i=0; $i<100; $i++) {

            $users = $repo->findByRole('ROLE_INVESTOR');
            $syndicate = new Syndicate();
            $syndicate->setUser($users[array_rand($users)]);
            $syndicate->setName($faker->name);
            $syndicate->setBody($faker->text(100,1000));

            $investors = $repo->findByRole('ROLE_INVESTOR', rand(5,count($users)));
            foreach($investors as $investor) {
                $syndicate->addInvestor($investor);
            }

            $manager->persist($syndicate);

        }

        $manager->flush();

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 99; // the order in which fixtures will be loaded
    }

}
