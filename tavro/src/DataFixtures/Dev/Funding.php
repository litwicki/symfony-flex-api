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
