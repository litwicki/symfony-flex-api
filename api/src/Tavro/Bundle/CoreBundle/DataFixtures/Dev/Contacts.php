<?php

namespace Tavro\Bundle\CoreBundle\DataFixtures\Dev;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Tavro\Bundle\CoreBundle\Entity\Person;
use Tavro\Bundle\CoreBundle\Entity\Comment;
use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Entity\Role;
use Tavro\Bundle\CoreBundle\Entity\Variable;
use Tavro\Bundle\CoreBundle\Entity\Organization;
use Tavro\Bundle\CoreBundle\Entity\Account;
use Tavro\Bundle\CoreBundle\Entity\AccountUser;
use Tavro\Bundle\CoreBundle\Entity\Shareholder;
use Tavro\Bundle\CoreBundle\Entity\Product;
use Tavro\Bundle\CoreBundle\Entity\Service;
use Tavro\Bundle\CoreBundle\Entity\Expense;
use Tavro\Bundle\CoreBundle\Entity\Node;
use Tavro\Bundle\CoreBundle\Entity\Revenue;
use Tavro\Bundle\CoreBundle\Entity\Tag;
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
use Tavro\Bundle\CoreBundle\Entity\FundingRoundShareholder;

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
