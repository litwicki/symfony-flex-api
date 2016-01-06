<?php

namespace Tavro\Bundle\CoreBundle\DataFixtures\ORM\Dev\Nodes;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Entity\Role;
use Tavro\Bundle\CoreBundle\Entity\Variable;
use Tavro\Bundle\CoreBundle\Entity\Mod;
use Tavro\Bundle\CoreBundle\Entity\Node;
use Tavro\Bundle\CoreBundle\Entity\NodeTag;
use Tavro\Bundle\CoreBundle\Entity\Comment;
use Tavro\Bundle\CoreBundle\Entity\ModComment;
use Tavro\Bundle\CoreBundle\Entity\NodeComment;

use Litwicki\Common\Common;

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
    public function setContainer(ContainerInterface $container = NULL)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $loremIpsum = $this->container->get('apoutchika.lorem_ipsum');
        $users = $manager->getRepository('TavroCoreBundle:User')->findAll();
        $nodeTypes = array('article', 'guide');

        for($i=0; $i<50; $i++) {

            $status = rand(0,2);
            $node = new Node();
            $node->setTitle($loremIpsum->getWords(5,10));
            $node->setBody($loremIpsum->getParagraphs(5,15));
            $node->setUser($users[array_rand($users)]);
            $node->setType($nodeTypes[array_rand($nodeTypes)]);
            $node->setStatus($status);
            //$node->setSlug(Common::createUrlSlug($node->getTitle(), $node->getId()));
            if($status === 1) {
                $node->setDisplayDate(new \DateTime());
            }

            $manager->persist($node);

            $nodes[] = $node;

        }

        $manager->flush();

        /**
         * Re-save ever node so the slug is properly updated with the {ID}
         */
        foreach($nodes as $node) {
            $node->setSlug(sprintf('%s-%s', $node->getId(), $node->getSlug()));
            $manager->persist($node);
        }

        $manager->flush();

        foreach($nodes as $node) {

            $commentCount = rand(0,25);

            for($n=0; $n<$commentCount; $n++) {
                $comment = new Comment();
                $comment->setBody($loremIpsum->getParagraphs(1,4));
                $comment->setTitle($loremIpsum->getWords(2,5));
                $comment->setUser($users[array_rand($users)]);
                //$comment->setSlug(Common::createUrlSlug($comment->getTitle(), $comment->getId()));
                $manager->persist($comment);
                $nodeComment = new NodeComment();
                $nodeComment->setNode($node);
                $nodeComment->setComment($comment);
                $manager->persist($nodeComment);
            }

            $manager->flush();

        }

        /**
         * Create dummy tags for this Node.
         */
        $tags = $manager->getRepository('TavroCoreBundle:Tag')->findAll();

        foreach($nodes as $node) {

            for($x=0;$x<rand(0,3);$x++) {
                $nt = new NodeTag();
                $nt->setNode($node);
                $nt->setTag($tags[array_rand($tags)]);
                $manager->persist($nt);
            }

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