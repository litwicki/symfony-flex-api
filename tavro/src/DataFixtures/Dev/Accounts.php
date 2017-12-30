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
use Tavro\Entity\Account;
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
class Accounts extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        /**
         * Get AccountTypes
         */

        $types = $manager->getRepository('TavroCoreBundle:AccountType')->findAll();

        /**
         * Create Zoadilack
         */

        $bot = $manager->getRepository('TavroCoreBundle:User')->findOneBy([
            'username' => 'tavrobot'
        ]);

        $saas = $manager->getRepository('TavroCoreBundle:AccountType')->findOneBy([
            'name' => 'Software as a Service (SaaS)'
        ]);

        $account = new Account();
        $account->setType($saas);
        $account->setName('Zoadilack');
        $account->setUser($bot);
        $manager->persist($account);
        $manager->flush();

        $faker = \Faker\Factory::create('en_EN');

        $users = $manager->getRepository('TavroCoreBundle:User')->findByRole('ROLE_USER');

        for($i=0; $i<5; $i++) {

            $type = $types[array_rand($types)];

            $account = new Account();
            $account->setType($type);
            $account->setName($faker->company);
            $account->setUser($users[array_rand($users)]);

            /**
             * Randomly set some accounts to have a trial period.
             */
            $trial = (rand(0,1) == 1 ? true : false);

            if($trial) {
                $start = new \DateTime();
                $end = $start;
                $start->sub(new \DateInterval(sprintf('P%sD', rand(1,30))));
                $end->add(new \DateInterval(sprintf('P%sD', rand(30,60))));
                $account->setTrialStartDate($start);
                $account->setTrialEndDate($end);
            }

            $manager->persist($account);

        }

        $manager->flush();

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }

}
