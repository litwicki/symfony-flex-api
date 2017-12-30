<?php

namespace Tavro\DataFixtures\Dev;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Tavro\Entity\Person;
use Tavro\Entity\Comment;
use Tavro\Entity\User;
use Tavro\Entity\Role;
use Tavro\Entity\Variable;
use Tavro\Entity\Organization;
use Tavro\Entity\Account;
use Tavro\Entity\AccountUser;
use Tavro\Entity\Shareholder;
use Tavro\Entity\Product;
use Tavro\Entity\Service;
use Tavro\Entity\Expense;
use Tavro\Entity\Node;
use Tavro\Entity\Revenue;
use Tavro\Entity\Tag;
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
use Tavro\Entity\FundingRoundShareholder;

use Cocur\Slugify\Slugify;



/**
 * Defines all predefined installed types created during install.
 *
 * @author jake.litwicki@gmail.com
 */
class Contacts extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $size = 25;

        $accounts = $manager->getRepository('TavroCoreBundle:Account')->findAll();
        $genders = array('male', 'female');

        $faker = \Faker\Factory::create('en_EN');

        $people = array();

        foreach($accounts as $account) {

            $users = $account->getUsers()->toArray();

            $organizations = $account->getOrganizations();

            foreach($organizations as $organization) {

                for($i=0;$i<rand(0,$size);$i++) {

                    $person = new Person();
                    $person->setFirstName($faker->firstName);
                    $person->setLastName($faker->lastName);
                    $person->setAddress($faker->streetAddress);
                    $person->setCity($faker->city);
                    $person->setState($faker->state);
                    $person->setZip($faker->postcode);
                    $person->setEmail($faker->email);
                    $person->setPhone($faker->phoneNumber);
                    $person->setBirthday(new \DateTime($faker->dateTimeThisCentury->format('Y-m-d H:i:s')));
                    $person->setGender($genders[rand(0,1)]);
                    $manager->persist($person);
                    $people[] = $person;

                }

                $manager->flush();

                foreach($people as $person) {

                    $contact = new Contact();
                    $contact->setStatus(rand(0,1));
                    $contact->setOrganization($organization);
                    $contact->setPerson($person);
                    $contact->setJobTitle($faker->jobTitle);
                    $contact->setPhone($faker->phoneNumber);
                    $contact->setEmail($faker->email);
                    $manager->persist($contact);
                    $contacts[] = $contact;
                    $manager->persist($contact);

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
        return 8; // the order in which fixtures will be loaded
    }

}
