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
use App\Entity\NodeTag;
use App\Entity\ProductCategory;
use App\Entity\RevenueCategory;
use App\Entity\ServiceCategory;
use App\Entity\Contact;
use App\Entity\OrganizationComment;
use App\Entity\FundingRoundShareholder;
use App\Entity\RevenueService;
use App\Entity\RevenueProduct;

use Cocur\Slugify\Slugify;


/**
 * Defines all predefined installed types created during install.
 *
 * @author jake.litwicki@gmail.com
 */
class Organizations extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $organizations = array();

        foreach($accounts as $account) {

            $users = $account->getUsers()->toArray();

            for($i=0;$i<rand(1,$size);$i++) {

                $organization = new Organization();
                $organization->setName($faker->company);
                $organization->setBody($faker->text(rand(100,1000)));
                $organization->setAccount($account);
                $organization->setStatus(rand(0,1));
                $manager->persist($organization);
                $organizations[] = $organization;

            }

            $manager->flush();

        }

        foreach($organizations as $organization) {

            for($i=0;$i<rand(1,$size);$i++) {

                $comment = new Comment();
                $comment->setUser($users[array_rand($users, 1)]);
                $comment->setBody($faker->text(rand(100,1000)));
                $comment->setStatus(rand(0,1));
                $manager->persist($comment);
                $manager->flush();

                $orgComment = new OrganizationComment();
                $orgComment->setOrganization($organization);
                $orgComment->setComment($comment);
                $manager->persist($orgComment);

            }

            $manager->flush();

        }

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 7; // the order in which fixtures will be loaded
    }

}