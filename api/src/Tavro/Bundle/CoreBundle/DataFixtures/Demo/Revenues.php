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
class Revenues extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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

        $organizations = $manager->getRepository('TavroCoreBundle:Organization')->findAll();

        foreach($organizations as $organization) {

            $revenueCategories = $organization->getRevenueCategories()->toArray();
            $users = $organization->getUsers();
            $services = $organization->getServices()->toArray();
            $products = $organization->getProducts()->toArray();

            $revenues = array();
            $revenueTypes = array('sale', 'service', 'other');

            for($i=0;$i<$size;$i++) {

                $revenue = new Revenue();
                $revenue->setOrganization($organization);
                $revenue->setStatus(rand(0,1));
                $revenue->setType($revenueTypes[rand(0,2)]);
                $revenue->setCreateDate(new \DateTime());
                $revenue->setUser($users[array_rand($users)]);
                $revenue->setTitle(ucwords($lipsum->getWords(rand(1,5))));
                $revenue->setBody($lipsum->getParagraphs(rand(1,3)));
                $revenue->setCategory($revenueCategories[array_rand($revenueCategories)]);
                $manager->persist($revenue);
                $revenues[] = $revenue;

            }

            $manager->flush();

            $types = array('product', 'service');

            foreach($revenues as $revenue) {

                for($i=0;$i<rand(1,$size);$i++) {

                    $type = $types[array_rand($types)];

                    if($type == 'service') {
                        if(count($services)) {
                            $entity = new RevenueService();
                            $entity->setService($services[array_rand($services)]);
                            $entity->setRevenue($revenue);
                            $manager->persist($entity);
                        }
                    }
                    else {
                        if(count($products)) {
                            $entity = new RevenueProduct();
                            $entity->setProduct($products[array_rand($products)]);
                            $entity->setRevenue($revenue);
                            $manager->persist($entity);
                        }
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
        return 13; // the order in which fixtures will be loaded
    }
}
