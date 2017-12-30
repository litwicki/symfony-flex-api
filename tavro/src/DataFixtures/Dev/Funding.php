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

use Cocur\Slugify\Slugify;


/**
 * Defines all predefined installed types created during install.
 *
 * @author jake.litwicki@gmail.com
 */
class Funding extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $faker = \Faker\Factory::create('en_EN');
        $size = 10;

        $accounts = $manager->getRepository('TavroCoreBundle:Account')->findAll();
        $users = $manager->getRepository('TavroCoreBundle:User')->findAll();
        $shareholders = $manager->getRepository('TavroCoreBundle:Shareholder')->findAll();

        foreach($accounts as $account) {

            $rounds = array();

            for($i=0;$i<$size;$i++) {

                $funding = new FundingRound();
                $funding->setAccount($account);
                $funding->setCreateDate(new \DateTime());
                $funding->setStatus(1);
                $funding->setBody($faker->text(rand(100,1000)));
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
                    $comment->setBody($faker->text(rand(100,1000)));
                    $comment->setStatus(rand(0,1));
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

        }

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 9; // the order in which fixtures will be loaded
    }

}
