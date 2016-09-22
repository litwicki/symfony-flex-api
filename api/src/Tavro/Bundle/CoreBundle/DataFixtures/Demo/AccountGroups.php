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
use Tavro\Bundle\CoreBundle\Entity\AccountGroup;
use Tavro\Bundle\CoreBundle\Entity\AccountGroupUser;

use Cocur\Slugify\Slugify;
use Litwicki\Common\Common as Litwicki;

/**
 * Defines all predefined installed types created during install.
 *
 * @author jake.litwicki@gmail.com
 */
class AccountGroupsAndUsers extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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

        $autobots = $manager->getRepository('TavroCoreBundle:Account')->findOneBy([
            'name' => 'Autobots'
        ]);

        $groups = [
            'Cars' => [
                'Bluestreak',
                'Hound',
                'Ironhide',
                'Jazz',
                'Mirage',
                'Prowl',
                'Ratchet',
                'Sideswipe',
                'Sunstreaker',
                'Wheeljack',
                'Hoist',
                'Red Alert',
                'Smokescreen',
                'Tracks',
                'Blurr',
                'Hot Rod',
                'Kup',
            ],
            'Mini-Vehicles' => [
                'Brawn',
                'Bumblebee',
                'Cliffjumper',
                'Gears',
                'Windcharger',
                'Beachcomber',
                'Cosmos',
                'Warpath',
                'Wheelie'
            ],
            'Aerialbots' => [
                'Silverbolt',
                'Air Raid',
                'Firefight',
                'Skydive',
                'Slingshot'
            ]
        ];

        foreach($groups as $name => $users) {

            $group = new AccountGroup();
            $group->setName($name);
            $group->setAccount($autobots);
            $manager->persist($group);
            $manager->flush();

            foreach($users as $username) {

                $groupUser = new AccountGroupUser();
                $groupUser->setUser($user);
                $groupUser->setGroup($group);
                $manager->persist($groupUser);

            }

            $manager->flush();

        }



        $decepticons = $manager->getRepository('TavroCoreBundle:Account')->findOneBy([
            'name' => 'Decepticons'
        ]);

        $groups = [
            'Constructicons' => [
                'Scrapper',
                'Bonecrusher',
                'Hook',
                'Long Haul',
                'Mixmaster',
                'Scavenger'
            ],
            'Combaticons' => [
                'Onslaught',
                'Brawl',
                'Swindle',
                'Blast Off',
                'Vortex',
            ],
            'Stunticons' => [
                'Motormaster',
                'Breakdown',
                'Drag Strip',
                'Dead End',
                'Wildrider'
            ],
            'Predacons' => [
                'Razorclaw',
                'Divebomb',
                'Headstrong',
                'Rampage',
                'Tantrum'
            ]
        ];

        foreach($groups as $name) {
            $group = new AccountGroup();
            $group->setName($name);
            $group->setAccount($autobots);
            $manager->persist($group);
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
