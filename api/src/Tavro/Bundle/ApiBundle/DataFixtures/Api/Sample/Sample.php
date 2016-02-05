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

use Cocur\Slugify\Slugify;

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

            $username = $lipsum->getWords(1);
            $email = sprintf('%s@tavro.dev', $username);

            $user = new User();
            $user->setStatus(rand(0,1));
            $user->setCreateDate($now);
            $user->setApiEnabled(rand(0,1));
            $user->setEmail($email);
            $user->setUsername($username);
            $user->setGender($genders[rand(0,1)]);
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
            $organizations[] = $organization;

        }

        $manager->flush();


    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }

}
