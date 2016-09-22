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
use Tavro\Bundle\CoreBundle\Entity\NodeTag;
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

        foreach($accounts as $account) {

            $tags = $account->getTags()->toArray();
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
