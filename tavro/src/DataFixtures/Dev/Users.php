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
use Tavro\Entity\RevenueService;
use Tavro\Entity\RevenueProduct;
use Tavro\Entity\Person;

use Cocur\Slugify\Slugify;


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
        $admin = $manager->getRepository('TavroCoreBundle:Role')->findOneBy([
            'role' => 'ROLE_ADMIN',
        ]);

        $developer = $manager->getRepository('TavroCoreBundle:Role')->findOneBy([
            'role' => 'ROLE_DEVELOPER',
        ]);

        $investor = $manager->getRepository('TavroCoreBundle:Role')->findOneBy([
            'role' => 'ROLE_INVESTOR',
        ]);

        $tavro = $manager->getRepository('TavroCoreBundle:Role')->findOneBy([
            'role' => 'ROLE_TAVRO',
        ]);

        $userRole = $manager->getRepository('TavroCoreBundle:Role')->findOneBy([
            'role' => 'ROLE_USER',
        ]);

        $roles = [$developer, $investor, $userRole];

        //create 100 users
        for($i=0; $i<100; $i++) {
            $this->create($manager, $roles[array_rand($roles)]);
        }

        //create tavrobot!
        $this->create($manager, $admin, [
            'username' => 'tavrobot',
            'email' => 'dev@zoadilack.com',
            'first_name' => 'Tavro',
            'last_name' => 'Bot'
        ]);

        //create hutch!
        $this->create($manager, $tavro, [
            'username' => 'hutch.white',
            'email' => 'hutch@zoadilack.com',
            'first_name' => 'Hutch',
            'last_name' => 'White'
        ]);

        //create jake!
        $this->create($manager, $tavro, [
            'username' => 'jake.litwicki',
            'email' => 'jake@zoadilack.com',
            'first_name' => 'Jake',
            'last_name' => 'Litwicki'
        ]);

    }

    public function create(ObjectManager $manager, Role $role, array $parameters = array())
    {
        try {

            $faker = \Faker\Factory::create('en_EN');

            $password = isset($parameters['password']) ? $parameters['password'] : 'Password1!';

            $firstname = isset($parameters['first_name']) ? $parameters['first_name'] : $faker->unique()->firstName;
            $lastname = isset($parameters['last_name']) ? $parameters['last_name'] : $faker->unique()->lastName;

            $email = isset($parameters['email']) ? $parameters['email'] : sprintf('%s.%s@tavro.io', strtolower($firstname), strtolower($lastname));
            //$email = isset($parameters['email']) ? $parameters['email'] : $faker->safeEmail;

            $gender = isset($parameters['gender']) ? $parameters['gender'] : 'male';
            $username = isset($parameters['username']) ? $parameters['username'] : $email;

            $salt = md5($email);
            $encoder = $this->container->get('tavro.password_encoder');
            $password = $encoder->encodePassword($password, $salt);

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
            $user->setUsername($username);
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
