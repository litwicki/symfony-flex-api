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
use App\Entity\Person;

use Cocur\Slugify\Slugify;

/**
 * Defines all predefined installed types created during install.
 *
 * @author jake.litwicki@gmail.com
 */
class Shareholders extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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

        $shareholders = array();
        $people = array();

        $faker = \Faker\Factory::create('en_EN');
        $genders = array('male', 'female');

        for($i=0;$i<50;$i++) {
            $person = new Person();
            $person->setFirstName($faker->firstName);
            $person->setLastName($faker->lastName);
            $person->setAddress($faker->streetAddress);
            $person->setCity($faker->city);
            $person->setState($faker->state);
            $person->setZip($faker->postcode);
            $person->setEmail($faker->email);
            $person->setPhone($faker->phoneNumber);
            $person->setBirthday($faker->dateTimeThisCentury);
            $person->setGender($genders[rand(0,1)]);
            $manager->persist($person);
            $people[] = $person;
        }

        $manager->flush();

        foreach($people as $person) {
            $shareholder = new Shareholder();
            $shareholder->setBody($faker->text(rand(100,1000)));
            $shareholder->setCreateDate(new \DateTime());
            $shareholder->setPerson($person);
            $manager->persist($shareholder);
            $shareholders[] = $shareholder;
        }

        $manager->flush();

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 14; // the order in which fixtures will be loaded
    }

}
