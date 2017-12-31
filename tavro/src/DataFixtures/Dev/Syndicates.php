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
use App\Entity\NodeTag;
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
use App\Entity\Syndicate;

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
