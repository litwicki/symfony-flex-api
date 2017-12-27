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
use Tavro\Bundle\CoreBundle\Entity\NodeTag;
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
use Tavro\Bundle\CoreBundle\Entity\Syndicate;

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
