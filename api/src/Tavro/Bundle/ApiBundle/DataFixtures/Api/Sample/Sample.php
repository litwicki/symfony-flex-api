<?php

namespace Tavro\Bundle\ApiBundle\DataFixtures\Api\Sample;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Entity\Role;
use Tavro\Bundle\CoreBundle\Entity\Variable;
use Tavro\Bundle\CoreBundle\Entity\Organization;
use Tavro\Bundle\CoreBundle\Entity\OrganizationShareholder;
use Tavro\Bundle\CoreBundle\Entity\Shareholder;
use Tavro\Bundle\CoreBundle\Entity\Product;
use Tavro\Bundle\CoreBundle\Entity\Service;
use Tavro\Bundle\CoreBundle\Entity\Expense;
use Tavro\Bundle\CoreBundle\Entity\Node;
use Tavro\Bundle\CoreBundle\Entity\Revenue;
use Tavro\Bundle\CoreBundle\Entity\Tag;
use Tavro\Bundle\CoreBundle\Entity\UserOrganization;
use Tavro\Bundle\CoreBundle\Entity\ExpenseCategory;
use Tavro\Bundle\CoreBundle\Entity\ExpenseTag;
use Tavro\Bundle\CoreBundle\Entity\FundingRound;
use Tavro\Bundle\CoreBundle\Entity\FundingRoundComment;
use Tavro\Bundle\CoreBundle\Entity\NodeComment;
use Tavro\Bundle\CoreBundle\Entity\ProductCategory;
use Tavro\Bundle\CoreBundle\Entity\RevenueCategory;
use Tavro\Bundle\CoreBundle\Entity\ServiceCategory;
use Tavro\Bundle\CoreBundle\Entity\Customer;
use Tavro\Bundle\CoreBundle\Entity\CustomerComment;

use Cocur\Slugify\Slugify;
use Litwicki\Common\Common as Litwicki;

/**
 * Defines all predefined installed types created during install.
 *
 * @author jake.litwicki@gmail.com
 */
class Sample extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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

    public function getCities($state)
    {
        $json = file_get_contents(sprintf('http://api.sba.gov/geodata/city_links_for_state_of/%s.json', $state));
        $data = json_decode($json, true);
        $cities = array();
        foreach($data as $item) {
            $cities[] = $item['name'];
        }
        return $cities;
    }

    public function getStates()
    {
        $states = Litwicki::getStateSelectChoices();
        $states = array_keys($states);
        unset($states['AS']);
        unset($states['AP']);
        unset($states['AA']);
        unset($states['AE']);
        unset($states['DC']);
        unset($states['PR']);
        unset($states['VI']);
        return $states;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $lipsum = $this->container->get('apoutchika.lorem_ipsum');
        $size = 10;

        $organizations = [];
        $users = [];

        $now = new \DateTime();
        $genders = array('male', 'female');

        for($i=0;$i<$size;$i++) {

            $username = sprintf('user%s', $i);
            $email = sprintf('%s@tavro.dev', $username);
            $salt = md5($email);
            $password = 'Password1!';
            $encoder = $this->container->get('tavro.password_encoder');
            $password = $encoder->encodePassword($password, $salt);

            $user = new User();
            $user->setStatus(rand(0,1));
            $user->setCreateDate($now);
            $user->setApiEnabled(rand(0,1));
            $user->setEmail($email);
            $user->setUsername($username);
            $user->setGender($genders[rand(0,1)]);
            $user->setSalt($salt);
            $user->setPassword($password);
            $manager->persist($user);
            $users[] = $user;
        }

        $manager->flush();

        for($i=0;$i<$size;$i++) {

            $organization = new Organization();
            $organization->setTitle($lipsum->getWords(1,10));
            $organization->setBody($lipsum->getSentences(rand(1,5)));
            $organization->setCreateDate($now);
            $organization->setOwner($users[rand(0,$size-1)]);
            $organization->setStatus(rand(0,1));
            $organization->setUpdateDate($users[rand(0,$size-1)]);
            $manager->persist($organization);
            $organizations[] = $organization;

        }

        $manager->flush();

        foreach($organizations as $organization) {

            for($i=0;$i<rand(0,$size);$i++) {
                $user = $users[array_rand($users)];
                $uo = new UserOrganization();
                $uo->setUser($user);
                $uo->setOrganization($organization);
                $uo->setStatus(rand(0,1));
                $uo->setCreateDate($now);
                $manager->persist($uo);
            }

            $manager->flush();

            $expenseCategories = array();
            $revenueCategories = array();
            $serviceCategories = array();
            $productCategories = array();

            for($i=0;$i<5;$i++) {
                $productCategory = new ProductCategory();
                $productCategory->setOrganization($organization);
                $productCategory->setCreateDate($now);
                $productCategory->setStatus(1);
                $productCategory->setBody($lipsum->getWords(rand(1,5)));
                $manager->persist($productCategory);
                $productCategories[] = $productCategory;
            }

            $manager->flush();

            for($i=0;$i<5;$i++) {
                $serviceCategory = new ServiceCategory();
                $serviceCategory->setOrganization($organization);
                $serviceCategory->setCreateDate($now);
                $serviceCategory->setStatus(1);
                $serviceCategory->setBody($lipsum->getWords(rand(1,5)));
                $manager->persist($serviceCategory);
                $serviceCategories[] = $serviceCategory;
            }

            $manager->flush();

            for($i=0;$i<5;$i++) {
                $revenueCategory = new RevenueCategory();
                $revenueCategory->setOrganization($organization);
                $revenueCategory->setCreateDate($now);
                $revenueCategory->setStatus(1);
                $revenueCategory->setBody($lipsum->getWords(rand(1,5)));
                $manager->persist($revenueCategory);
                $revenueCategories[] = $revenueCategory;
            }

            $manager->flush();

            for($i=0;$i<5;$i++) {
                $expenseCategory = new ExpenseCategory();
                $expenseCategory->setOrganization($organization);
                $expenseCategory->setCreateDate($now);
                $expenseCategory->setStatus(1);
                $expenseCategory->setBody($lipsum->getWords(rand(1,5)));
                $manager->persist($expenseCategory);
                $expenseCategories[] = $expenseCategory;
            }

            $manager->flush();

            for($i=0;$i<$size;$i++) {

                $name = $lipsum->getWords(1);
                $email = sprintf('%s@tavro-customer.dev', $name);

                $cities = $this->getCities('WA');

                $customer = new Customer();
                $customer->setEmail($email);
                $customer->setFirstName($name);
                $customer->setLastName($lipsum->getWords(1));
                $customer->setStatus(rand(0,1));
                $customer->setCreateDate($now);
                $customer->setAddress($lipsum->getWords(rand(1,3)));
                $customer->setCity($cities[array_rand($cities)]);
                $customer->setState('WA');
                $customer->setZip(rand(11111,99999));
                $customer->setOrganization($organization);
                $customer->setPhone(sprintf('(%s) %s-%s', rand(111,999), rand(111,999), rand(1111,9999)));
                $manager->persist($customer);
                $customers[] = $customer;

            }

            $manager->flush();

//            for($i=0;$i<$size;$i++) {
//
//                $expenseDate = $now;
//                $category = $expenseCategories[array_rand($expenseCategories)];
//
//                $expense = new Expense();
//                $expense->setOrganization($organization);
//                $expense->setCreateDate($now);
//                $expense->setStatus(rand(0,1));
//                $expense->setAmount(rand(0,9999));
//                $expense->setExpenseDate($expenseDate->add(\DateInterval::createFromDateString(sprintf('-%s days', rand(1,90)))));
//                $expense->setCategory($category);
//                $expense->setUser($users[array_rand($users)]);
//                $manager->persist($expense);
//            }
//
//            $manager->flush();

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
