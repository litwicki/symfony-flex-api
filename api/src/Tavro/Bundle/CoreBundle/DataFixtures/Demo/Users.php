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
use Tavro\Bundle\CoreBundle\Entity\CustomerComment;
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
        $size = 10;

        $organizations = [];
        $users = [];

        $genders = array('male', 'female');
        $faker = \Faker\Factory::create('en_EN');

        $userRole = $manager->getRepository('TavroCoreBundle:Role')->findOneBy(array(
            'role' => 'ROLE_USER',
        ));

        $developerRole = $manager->getRepository('TavroCoreBundle:Role')->findOneBy(array(
            'role' => 'ROLE_DEVELOPER',
        ));

        $roles = array($userRole, $developerRole);

        $people = array();

        for($i=1;$i<$size;$i++) {
            $email = $faker->email;
            $person = new Person();
            $gender = $genders[rand(0,1)];
            $person->setFirstName($faker->firstName);
            $person->setLastName($faker->lastName);
            $person->setTitle($faker->title($gender));
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
             * Only do this for test Users!!!
             */
            $user->setApiKey($person->getEmail());

            $user->setPassword($password);
            $user->setPerson($person);
            $user->addRole($roles[array_rand($roles)]);
            $manager->persist($user);
            $users[] = $user;
        }

        $admin = $manager->getRepository('TavroCoreBundle:Role')->findOneBy(array(
            'role' => 'ROLE_ADMIN',
        ));

        /**
         * Add TavroBot
         */
        $username = 'tavrobot';
        $email = 'bot@tavro.dev';
        $salt = md5($email);
        $password = 'Password1!';
        $encoder = $this->container->get('tavro.password_encoder');
        $password = $encoder->encodePassword($password, $salt);

        $person = new Person();
        $gender = 'robot';
        $person->setFirstName($faker->firstName);
        $person->setLastName($faker->lastName);
        $person->setTitle($faker->title($gender));
        $person->setSuffix($faker->suffix);
        $person->setEmail('bot@tavro.dev');
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

        $user->addRole($admin);

        $manager->persist($user);

        /**
         * Add Fembot
         */
        $username = 'fembot';
        $email = 'fembot@tavro.dev';
        $salt = md5($email);
        $password = 'Password1!';
        $encoder = $this->container->get('tavro.password_encoder');
        $password = $encoder->encodePassword($password, $salt);

        $person = new Person();
        $gender = 'robot';
        $person->setFirstName($faker->firstName);
        $person->setLastName($faker->lastName);
        $person->setTitle($faker->title($gender));
        $person->setSuffix($faker->suffix);
        $person->setEmail('fembot@tavro.dev');
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

        $user->addRole($admin);

        $manager->persist($user);

        $manager->flush();

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}
