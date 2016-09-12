<?php

namespace Tavro\Bundle\CoreBundle\DataFixtures\Demo;

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
use Litwicki\Common\Common as Litwicki;
use Tavro\Bundle\CoreBundle\Model\AccountEntity;

/**
 * Defines all predefined installed types created during install.
 *
 * @author jake.litwicki@gmail.com
 */
class AccountUsers extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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

        $size = 10;

        $accounts = $manager->getRepository('TavroCoreBundle:Account')->findAll();

        $autobots = $manager->getRepository('TavroCoreBundle:User')->findBy([
            'gender' => 'autobot'
        ]);

        $decepticons = $manager->getRepository('TavroCoreBundle:User')->findBy([
            'gender' => 'decepticon'
        ]);

        $users = $manager->getRepository('TavroCoreBundle:User')->findBy([
            'gender' => ['male', 'female']
        ]);

        foreach($accounts as $account) {

            for($i=0;$i<$size;$i++) {

                switch($account->getName()) {
                    case 'Decepticons':
                        $user = $decepticons[array_rand($decepticons)];
                        break;
                    case 'Autobots':
                        $user = $autobots[array_rand($autobots)];
                        break;
                    default:
                        $user = $users[array_rand($users)];
                }

                /**
                 * Don't create an AccountUser for someone who is already the Owner of the Account.
                 *
                 */
                if($account->getUser()->getId() != $user->getId()) {
                    $au = new AccountUser();
                    $au->setUser($user);
                    $au->setAccount($account);
                    $manager->persist($au);
                }

            }

        }

        $manager->flush();

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3; // the order in which fixtures will be loaded
    }

}