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

use Cocur\Slugify\Slugify;
use Litwicki\Common\Common as Litwicki;
use Tavro\Bundle\CoreBundle\Entity\Person;

/**
 * Defines all predefined installed types created during install.
 *
 * @author jake.litwicki@gmail.com
 */
class Customers extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $lipsum = $this->container->get('apoutchika.lorem_ipsum');
        $size = 10;

        $organizations = $manager->getRepository('TavroCoreBundle:Organization')->findAll();
        $tlds = array('dev', 'net', 'com', 'org');
        $genders = array('male', 'female');

        $faker = \Faker\Factory::create('en_EN');

        $people = array();

        foreach($organizations as $organization) {

            for($i=0;$i<$size;$i++) {

                $person = new Person();
                $person->setFirstName($faker->firstName);
                $person->setLastName($faker->lastName);
                $person->setAddress($faker->streetAddress);
                $person->setCity($faker->city);
                $person->setState($faker->state);
                $person->setZip($faker->postcode);
                $person->setEmail($faker->safeEmail);
                $person->setPhone($faker->phoneNumber);
                $person->setBirthday($faker->dateTimeThisCentury);
                $person->setGender($genders[rand(0,1)]);
                $manager->persist($person);
                $people[] = $person;

            }

            $manager->flush();

            foreach($people as $person) {
                $customer = new Customer();
                $customer->setStatus(rand(0,1));
                $customer->setCreateDate(new \DateTime());
                $customer->setOrganization($organization);
                $customer->setPerson($person);
                $manager->persist($customer);
                $customers[] = $customer;
                $manager->persist($customer);
            }

        }

        $manager->flush();

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 8; // the order in which fixtures will be loaded
    }

}
