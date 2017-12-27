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
