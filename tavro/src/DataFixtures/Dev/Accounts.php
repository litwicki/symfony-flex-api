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
use App\Entity\Account;
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
