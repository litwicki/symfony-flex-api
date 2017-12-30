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
use Tavro\Entity\AccountGroup;
use Tavro\Entity\AccountGroupUser;

use Cocur\Slugify\Slugify;


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

        $accounts = $manager->getRepository('TavroCoreBundle:Account')->findAll();

        foreach($accounts as $account) {

            $users = $manager->getRepository('TavroCoreBundle:User')->getNumberOfUsers(rand(1,10));

            foreach($users as $user) {
                $entity = new AccountUser();
                $entity->setAccount($account);
                $entity->setUser($user);
                $manager->persist($entity);
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
