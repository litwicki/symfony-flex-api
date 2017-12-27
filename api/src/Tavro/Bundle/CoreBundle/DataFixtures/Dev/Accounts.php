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
use Tavro\Bundle\CoreBundle\Entity\Account;
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
