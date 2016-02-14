<?php

namespace Tavro\Bundle\ApiBundle\DataFixtures\Demo;

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

use Cocur\Slugify\Slugify;
use Litwicki\Common\Common as Litwicki;

/**
 * Defines all predefined installed types created during install.
 *
 * @author jake.litwicki@gmail.com
 */
class Demo extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        return Litwicki::getStateSelectChoices();
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
            $user->setCreateDate(new \DateTime());
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
            $organization->setCreateDate(new \DateTime());
            $organization->setOwner($users[rand(0,$size-1)]);
            $organization->setStatus(rand(0,1));
            $organization->setUpdateDate($users[rand(0,$size-1)]);
            $manager->persist($organization);
            $organizations[] = $organization;

        }

        $manager->flush();

        $tags = array();

        for($i=0;$i<50;$i++) {

            $tag = new Tag();
            $tag->setTitle($lipsum->getWords(1));
            $tag->setBody($lipsum->getSentences(1));
            $tag->setCreateDate(new \DateTime());
            $tag->setStatus(1);
            $manager->persist($tag);
            $tags[] = $tag;

        }

        $manager->flush();

        $shareholders = array();

        for($i=0;$i<50;$i++) {

            $email = sprintf('shareholder-%s@shareholders.tavro.dev', $lipsum->getWords(1));
            $cities = $this->getCities('WA');

            $shareholder = new Shareholder();
            $shareholder->setTitle($lipsum->getSentences(1));
            $shareholder->setCreateDate(new \DateTime());
            $shareholder->setAddress($lipsum->getWords(rand(1,3)));
            $shareholder->setCity($cities[array_rand($cities)]);
            $shareholder->setState('WA');
            $shareholder->setZip(rand(11111,99999));
            $shareholder->setEmail($email);
            $shareholder->setPhone(sprintf('(%s) %s-%s', rand(111,999), rand(111,999), rand(1111,9999)));
            $manager->persist($shareholder);
            $shareholders[] = $shareholder;
        }

        foreach($organizations as $organization) {

            for($i=0;$i<rand(0,$size);$i++) {
                $user = $users[array_rand($users)];
                $uo = new UserOrganization();
                $uo->setUser($user);
                $uo->setOrganization($organization);
                $uo->setStatus(rand(0,1));
                $uo->setCreateDate(new \DateTime());
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
                $productCategory->setCreateDate(new \DateTime());
                $productCategory->setStatus(1);
                $productCategory->setBody($lipsum->getWords(rand(1,5)));
                $manager->persist($productCategory);
                $productCategories[] = $productCategory;
            }

            $manager->flush();

            for($i=0;$i<5;$i++) {
                $serviceCategory = new ServiceCategory();
                $serviceCategory->setOrganization($organization);
                $serviceCategory->setCreateDate(new \DateTime());
                $serviceCategory->setStatus(1);
                $serviceCategory->setBody($lipsum->getWords(rand(1,5)));
                $manager->persist($serviceCategory);
                $serviceCategories[] = $serviceCategory;
            }

            $manager->flush();

            for($i=0;$i<5;$i++) {
                $revenueCategory = new RevenueCategory();
                $revenueCategory->setOrganization($organization);
                $revenueCategory->setCreateDate(new \DateTime());
                $revenueCategory->setStatus(1);
                $revenueCategory->setBody($lipsum->getWords(rand(1,5)));
                $manager->persist($revenueCategory);
                $revenueCategories[] = $revenueCategory;
            }

            $manager->flush();

            for($i=0;$i<5;$i++) {
                $expenseCategory = new ExpenseCategory();
                $expenseCategory->setOrganization($organization);
                $expenseCategory->setCreateDate(new \DateTime());
                $expenseCategory->setStatus(1);
                $expenseCategory->setBody($lipsum->getWords(rand(1,5)));
                $manager->persist($expenseCategory);
                $expenseCategories[] = $expenseCategory;
            }

            $manager->flush();

            $nodeTypes = array(
                'article',
                'press',
                'wiki'
            );

            $nodes = array();

            for($i=0;$i<$size;$i++) {

                $node = new Node();
                $node->setTitle($lipsum->getWords(rand(3,10)));
                $node->setBody($lipsum->getParagraphs(rand(1,5)));
                $node->setStatus(rand(0,1));
                $node->setOrganization($organization);
                $node->setType($nodeTypes[array_rand($nodeTypes)]);
                $node->setCreateDate(new \DateTime());
                $node->setDisplayDate(new \DateTime());
                $node->setUser($users[array_rand($users)]);
                $node->setViews(rand(0,999999));
                $manager->persist($node);
                $nodes[] = $node;

            }

            $manager->flush();

            foreach($nodes as $node) {

                for($i=0;$i<rand(0,$size);$i++) {
                    $comment = new Comment();
                    $comment->setUser($users[array_rand($users)]);
                    $comment->setBody($lipsum->getSentences(rand(1,10)));
                    $comment->setStatus(rand(0,1));
                    $comment->setTitle($lipsum->getWords(rand(2,10)));
                    $manager->persist($comment);
                    $manager->flush();

                    $nodeComment = new NodeComment();
                    $nodeComment->setNode($node);
                    $nodeComment->setComment($comment);
                    $manager->persist($nodeComment);
                    $manager->flush();
                }

                for($i=0;$i<rand(0,$size);$i++) {
                    $nodeTag = new NodeTag();
                    $nodeTag->setNode($node);
                    $nodeTag->setTag($tags[array_rand($tags)]);
                    $manager->persist($nodeTag);
                }

            }

            $customers = array();

            for($i=0;$i<$size;$i++) {

                $name = $lipsum->getWords(1);
                $email = sprintf('%s@tavro-customer.dev', $name);

                $cities = $this->getCities('WA');

                $customer = new Customer();
                $customer->setEmail($email);
                $customer->setFirstName($name);
                $customer->setLastName($lipsum->getWords(1));
                $customer->setStatus(rand(0,1));
                $customer->setCreateDate(new \DateTime());
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

            $rounds = array();

            for($i=0;$i<$size;$i++) {

                $funding = new FundingRound();
                $funding->setOrganization($organization);
                $funding->setCreateDate(new \DateTime());
                $funding->setStatus(1);
                $funding->setBody($lipsum->getParagraphs(rand(1,5)));
                $funding->setSharePrice(rand(1,100));
                $funding->setTotalShares(rand(1000000,9999999));
                $funding->setType('seed');
                $manager->persist($funding);
                $rounds[] = $funding;

            }

            $manager->flush();

            foreach($rounds as $round) {

                $totalShares = $round->getTotalShares();

                for($i=0;$i<rand(0,$size);$i++) {
                    $comment = new Comment();
                    $comment->setUser($users[array_rand($users)]);
                    $comment->setBody($lipsum->getSentences(rand(1,10)));
                    $comment->setStatus(rand(0,1));
                    $comment->setTitle($lipsum->getWords(rand(2,10)));
                    $manager->persist($comment);
                    $manager->flush();

                    $frc = new FundingRoundComment();
                    $frc->setFundingRound($round);
                    $frc->setComment($comment);
                    $manager->persist($frc);
                    $manager->flush();
                }

                for($i=0;$i<rand(0,count($shareholders)-1);$i++) {

                    $shares = rand(100, $totalShares);
                    $availableShares = $totalShares - $shares;

                    if($availableShares >= 0) {
                        $frs = new FundingRoundShareholder();
                        $frs->setFundingRound($round);
                        $frs->setShareholder($shareholders[array_rand($shareholders)]);
                        $frs->setShares($shares);
                        $manager->persist($frs);
                    }

                }

                $manager->flush();

            }

            $expenses = array();

            for($i=0;$i<$size;$i++) {

                $expenseDate = new \DateTime();
                $category = $expenseCategories[array_rand($expenseCategories)];

                $expense = new Expense();
                $expense->setOrganization($organization);
                $expense->setCategory($category);
                $expense->setCreateDate(new \DateTime());
                $expense->setStatus(rand(0,1));
                $expense->setAmount(rand(0,9999));
                $expense->setExpenseDate($expenseDate->add(\DateInterval::createFromDateString(sprintf('-%s days', rand(1,90)))));
                $expense->setCategory($category);
                $expense->setUser($users[array_rand($users)]);
                $manager->persist($expense);
                $expenses[] = $expense;

            }

            $manager->flush();

            foreach($expenses as $expense) {

                for($i=0;$i<rand(0,$size);$i++) {
                    $comment = new Comment();
                    $comment->setUser($users[array_rand($users)]);
                    $comment->setBody($lipsum->getSentences(rand(1,10)));
                    $comment->setStatus(rand(0,1));
                    $comment->setTitle($lipsum->getWords(rand(2,10)));
                    $manager->persist($comment);
                    $manager->flush();

                    $ec = new ExpenseComment();
                    $ec->setExpense($expense);
                    $ec->setComment($comment);
                    $manager->persist($ec);
                    $manager->flush();
                }

                for($i=0;$i<rand(0,$size);$i++) {
                    $expenseTag = new ExpenseTag();
                    $expenseTag->setExpense($expense);
                    $expenseTag->setTag($tags[array_rand($tags)]);
                    $manager->persist($expenseTag);
                }

                $manager->flush();

            }

            $products = array();

            for($i=0;$i<rand(0,$size);$i++) {

                $category = $productCategories[array_rand($productCategories)];
                $cost = rand(0.99, 999.99);
                $product = new Product();
                $product->setCategory($category);
                $product->setCreateDate(new \DateTime());
                $product->setStatus(rand(0,1));
                $product->setTitle($lipsum->getWords(1,10));
                $product->setBody($lipsum->getParagraphs(1,3));
                $product->setOrganization($organization);
                $product->setCost($cost);
                $product->setPrice($cost * 1.15);
                $manager->persist($product);
                $products[] = $product;

            }

            $manager->flush();

            $services = array();

            for($i=0;$i<rand(0,$size);$i++) {

                $category = $serviceCategories[array_rand($serviceCategories)];
                $cost = rand(0.99, 999.99);
                $service = new Product();
                $service->setCategory($category);
                $service->setCreateDate(new \DateTime());
                $service->setStatus(rand(0,1));
                $service->setTitle($lipsum->getWords(1,10));
                $service->setBody($lipsum->getParagraphs(1,3));
                $service->setOrganization($organization);
                $service->setCost($cost);
                $service->setPrice($cost * 1.15);
                $manager->persist($service);
                $services[] = $service;

            }

            $manager->flush();

            $revenues = array();

            for($i=0;$i<rand(0,$size);$i++) {

                $revenue = new Revenue();
                $revenue->setOrganization($organization);
                $revenue->setTitle($lipsum->getWords(3,10));
                $revenue->setStatus(rand(0,1));
                $revenue->setCreateDate(new \DateTime());
                $revenue->setType('');
                $revenue->setBody($lipsum->getParagraphs((rand(1,3))));
                $revenue->setCategory($revenueCategories[array_rand($revenueCategories)]);
                $revenue->setCustomer($customers[array_rand($customers)]);
                $revenue->setUser($users[array_rand($users)]);
                $manager->persist($revenue);

            }

            $manager->flush();

            foreach($revenues as $revenue) {

                $service = rand(0,1);

                for($i=0;$i<rand(0,$size);$i++) {

                    if($service) {
                        $entity = new RevenueService();
                        $entity->setService($services[array_rand($services)]);
                    }
                    else {
                        $entity = new RevenueProduct();
                        $entity->setProduct($products[array_rand($products)]);
                    }

                    $entity->setRevenue($revenue);
                    $entity->setCreateDate(new \DateTime());
                    $manager->persist($entity);

                }

            }

            $manager->flush();

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