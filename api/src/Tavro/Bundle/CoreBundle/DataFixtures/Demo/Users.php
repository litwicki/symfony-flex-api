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
use Tavro\Bundle\CoreBundle\Entity\UserOrganization;
use Tavro\Bundle\CoreBundle\Entity\ExpenseCategory;
use Tavro\Bundle\CoreBundle\Entity\ExpenseComment;
use Tavro\Bundle\CoreBundle\Entity\ExpenseTag;
use Tavro\Bundle\CoreBundle\Entity\FundingRound;
use Tavro\Bundle\CoreBundle\Entity\FundingRoundComment;
use Tavro\Bundle\CoreBundle\Entity\NodeComment;
use Tavro\Bundle\CoreBundle\Entity\ProductCategory;
use Tavro\Bundle\CoreBundle\Entity\RevenueCategory;
use Tavro\Bundle\CoreBundle\Entity\ServiceCategory;
use Tavro\Bundle\CoreBundle\Entity\Customer;
use Tavro\Bundle\CoreBundle\Entity\OrganizationComment;
use Tavro\Bundle\CoreBundle\Entity\FundingRoundShareholder;
use Tavro\Bundle\CoreBundle\Entity\RevenueService;
use Tavro\Bundle\CoreBundle\Entity\RevenueProduct;
use Tavro\Bundle\CoreBundle\Entity\Person;

use Cocur\Slugify\Slugify;
use Litwicki\Common\Common as Litwicki;

/**
 * Defines all predefined installed types created during install.
 *
 * @author jake.litwicki@gmail.com
 */
class Users extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $userRole = $manager->getRepository('TavroCoreBundle:Role')->findOneBy(array(
            'role' => 'ROLE_USER',
        ));

        $admin = $manager->getRepository('TavroCoreBundle:Role')->findOneBy(array(
            'role' => 'ROLE_ADMIN',
        ));

        $people = array();
        $faker = \Faker\Factory::create('en_EN');
        $size = 10;
        $genders = ['male', 'female'];

        for($i=1;$i<$size;$i++) {
            $email = $faker->email;
            $person = new Person();
            $gender = $genders[rand(0,1)];
            $person->setFirstName($faker->firstName);
            $person->setLastName($faker->lastName);
            $person->setSuffix($faker->suffix);
            $person->setEmail($email);
            $person->setGender($gender);
            $person->setBirthday($faker->dateTimeThisCentury);
            $manager->persist($person);
            $people[] = $person;
        }

        $manager->flush();

        foreach($people as $person) {
            $salt = md5($person->getEmail());
            $password = 'Password1!';
            $encoder = $this->container->get('tavro.password_encoder');
            $password = $encoder->encodePassword($password, $salt);

            $user = new User();
            $user->setPerson($person);
            $user->setStatus(rand(0,1));
            $user->setCreateDate(new \DateTime());
            $user->setApiEnabled(rand(0,1));
            $user->setUsername($faker->userName);
            $user->setUserAgent($faker->userAgent);
            $user->setSalt($salt);

            /**
             * Only set the Api Key for test Users!!!
             */
            $user->setApiKey($person->getEmail());

            $user->setPassword($password);
            $user->setPerson($person);
            $user->addRole($userRole);
            $manager->persist($user);
        }

        $autobots = [
            'Optimus Prime',
            'Sentinel Prime',
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
            'Brawn',
            'Bumblebee'
        ];

        $decepticons = [
            'Megatron',
            'Soundwave',
            'Shockwave',
            'Skypwarp',
            'Starscream',
            'Thundercracker',
            'Reflector',
            'Thrust',
            'Ramjet',
            'Dirge'
        ];

        foreach($autobots as $name) {
            $username = str_replace(' ', '_', $name);
            $username = strtolower($username);
            $this->create($manager, $username, 'autobot', $userRole);
        }

        foreach($decepticons as $name) {
            $username = str_replace(' ', '_', $name);
            $username = strtolower($username);
            $this->create($manager, $username, 'decepticon', $userRole);
        }

        //create tavrobot!
        $this->create($manager, 'tavrobot', 'bot', $admin);

    }

    public function create($manager, $username, $gender, Role $role, $password = 'Password1!')
    {
        $faker = \Faker\Factory::create('en_EN');

        $email = sprintf('%s@tavro.dev', $username);
        $salt = md5($email);
        $encoder = $this->container->get('tavro.password_encoder');
        $password = $encoder->encodePassword($password, $salt);

        $person = new Person();
        $person->setFirstName($faker->firstName);
        $person->setLastName($faker->lastName);
        $person->setSuffix($faker->suffix);
        $person->setEmail($email);
        $person->setGender($gender);
        $person->setBirthday($faker->dateTimeThisCentury);
        $manager->persist($person);
        $manager->flush();

        $user = new User();
        $user->setPerson($person);
        $user->setStatus(1);
        $user->setCreateDate(new \DateTime());
        $user->setApiEnabled(1);
        $user->setApiKey('tavrobot-api-key');
        $user->setUsername($username);
        $user->setSalt($salt);
        $user->setPassword($password);
        $user->addRole($role);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}
