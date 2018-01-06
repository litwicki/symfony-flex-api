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
use App\Entity\NodeTag;
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

use Cocur\Slugify\Slugify;
use Litwicki\Common\Common as Litwicki;


/**
 * Defines all predefined installed types created during install.
 *
 * @author jake.litwicki@gmail.com
 */
class Nodes extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $data = json_decode($json, TRUE);
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
        $faker = \Faker\Factory::create('en_EN');
        $size = 10;

        $accounts = $manager->getRepository('TavroCoreBundle:Account')->findAll();
        $tags = $manager->getRepository('TavroCoreBundle:Tag')->findAll();

        foreach($accounts as $account) {

            $users = $account->getUsers()->toArray();

            $nodeTypes = array(
                'article',
                'press',
                'wiki'
            );

            $nodes = array();

            for($i=0;$i<$size;$i++) {

                $node = new Node();
                $node->setTitle($faker->title);
                $node->setBody($faker->text(rand(100,1000)));
                $node->setStatus(rand(0,1));
                $node->setAccount($account);
                $node->setType($nodeTypes[array_rand($nodeTypes)]);
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
                    $comment->setBody($faker->text(rand(100,1000)));
                    $comment->setStatus(rand(0,1));
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

                $manager->flush();

            }

        }

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 99; // the order in which fixtures will be loaded
    }

}
