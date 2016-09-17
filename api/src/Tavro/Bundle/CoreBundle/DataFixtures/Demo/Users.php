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
        $userRole = $manager->getRepository('TavroCoreBundle:Role')->findOneBy([
            'role' => 'ROLE_USER',
        ]);

        $admin = $manager->getRepository('TavroCoreBundle:Role')->findOneBy([
            'role' => 'ROLE_ADMIN',
        ]);

        $developer = $manager->getRepository('TavroCoreBundle:Role')->findOneBy([
            'role' => 'ROLE_DEVELOPER',
        ]);

        $people = array();
        $faker = \Faker\Factory::create('en_EN');
        $size = 10;
        $genders = ['male', 'female'];
        $roles = [$userRole, $developer];

        /**
         * These are "dummy" users we'll use for testing, but not actually
         * assigning to specific Accounts like the Transformers (below)
         * who we'll be testing functionally within their Account.
         */

        for($i=1;$i<$size;$i++) {
            $email = $faker->email;
            $person = new Person();
            $gender = $genders[rand(0,1)];
            $person->setFirstName($faker->firstName);
            $person->setLastName($faker->lastName);
            $person->setSuffix($faker->suffix);
            $person->setEmail($email);
            $person->setGender($gender);
            $person->setBirthday(new \DateTime($faker->dateTimeThisCentury->format('Y-m-d')));
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
            $user->setApiEnabled(rand(0,1));
            $user->setUsername($faker->userName);
            $user->setUserAgent($faker->userAgent);
            $user->setApiEnabled(rand(0,1));
            $user->setBody($faker->text(rand(100,1000)));
            $user->setLastOnlineDate(new \DateTime($faker->dateTimeThisMonth->format('Y-m-d H:i:s')));
            $user->setSignature($faker->text(rand(10,500)));
            $user->setSalt($salt);
            $user->setPassword($password);
            $user->addRole($roles[rand(0,1)]);
            $manager->persist($user);

        }

        $manager->flush();

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
            $email = sprintf('%s@autobots.tavro.dev', $username);
            $fullname = explode(' ', $name);
            $this->create($manager, $userRole, [
                'username' => $username,
                'email' => $email,
                'password' => $username,
                'first_name' => $fullname[0],
                'last_name' => isset($fullname[1]) ? $fullname[1] : NULL,
                'gender' => 'autobot',
            ]);
        }

        foreach($decepticons as $name) {
            $username = str_replace(' ', '_', $name);
            $username = strtolower($username);
            $email = sprintf('%s@decepticons.tavro.dev', $username);
            $fullname = explode(' ', $name);
            $this->create($manager, $userRole, [
                'username' => $username,
                'email' => $email,
                'password' => $username,
                'first_name' => $fullname[0],
                'last_name' => isset($fullname[1]) ? $fullname[1] : NULL,
                'gender' => 'decepticon',
            ]);
        }

        //create tavrobot!
        $this->create($manager, $admin, [
            'username' => 'tavrobot',
            'email' => 'dev@zoadilack.com'
        ]);

        //create hutch!
        $this->create($manager, $developer, [
            'username' => 'hutch.white',
            'email' => 'hutch@zoadilack.com'
        ]);

        //create jake!
        $this->create($manager, $developer, [
            'username' => 'jake.litwicki',
            'email' => 'jake@zoadilack.com'
        ]);

    }

    public function create(ObjectManager $manager, Role $role, array $parameters = array())
    {
        try {

            $faker = \Faker\Factory::create('en_EN');

            $password = isset($parameters['password']) ? $parameters['password'] : 'Password1!';
            $email = isset($parameters['email']) ? $parameters['email'] : sprintf('%s@tavro.io', $parameters['username']);

            $firstname = isset($parameters['first_name']) ? $parameters['first_name'] : $faker->firstName;
            $lastname = isset($parameters['last_name']) ? $parameters['last_name'] : $faker->lastName;
            $gender = isset($parameters['gender']) ? $parameters['gender'] : 'male';

            $salt = md5($email);
            $encoder = $this->container->get('tavro.password_encoder');
            $password = $encoder->encodePassword($password, $salt);

            $email = $faker->email;

            $person = new Person();
            $person->setFirstName($firstname);
            $person->setLastName($lastname);
            $person->setSuffix($faker->suffix);
            $person->setEmail($email);
            $person->setGender($gender);
            $person->setBirthday(new \DateTime($faker->dateTimeThisCentury->format('Y-m-d')));
            $manager->persist($person);
            $manager->flush();

            $user = new User();
            $user->setPerson($person);
            $user->setStatus(User::STATUS_ENABLED);
            $user->setCreateDate(new \DateTime());
            $user->setApiEnabled(TRUE);
            $user->setUsername($parameters['username']);
            $user->setSalt($salt);
            $user->setPassword($password);
            $user->addRole($role);
            $manager->persist($user);
            $manager->flush();

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}
