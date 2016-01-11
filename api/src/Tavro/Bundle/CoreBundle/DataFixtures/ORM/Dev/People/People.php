<?php

namespace Tavro\Bundle\CoreBundle\DataFixtures\ORM\Dev\People;

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
use Tavro\Bundle\CoreBundle\Entity\Comment;
use Tavro\Bundle\CoreBundle\Entity\ModComment;
use Tavro\Bundle\CoreBundle\Entity\NodeComment;

use Symfony\Component\Finder\Finder;

/**
 * Defines all predefined installed types created during install.
 *
 * @author jake.litwicki@gmail.com
 */
class People extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        /**
         * Create Users
         */
        $items = array(
            'user@tavro.dev' => array(
                'username' => 'user',
                'roles' => array('ROLE_USER'),
            ),
            'dev@tavro.dev' => array(
                'username' => 'dev',
                'roles' => array('ROLE_DEVELOPER'),
            ),
            'admin@tavro.dev' => array(
                'username' => 'admin',
                'roles' => array('ROLE_ADMIN'),
            ),
            'superuser@tavro.dev' => array(
                'username' => 'superuser',
                'roles' => array('ROLE_SUPERUSER'),
            ),
        );

        $handler = $this->container->get('tavro.handler.users');

        foreach($items as $email => $data) {
            $data['password'] = 'Password1!';
            $data['email'] = $email;
            $users[] = $handler->create($data);
        }

//        /**
//         * Generate a bunch of "fake" users.
//         */
//        $finder = new Finder();
//
//        //create the female users
//        $finder->files()->in('src/Tavro/Bundle/CoreBundle/Resources/public/img/avatars/female');
//        $this->createDummyUsers($manager, $finder);
//
//        //create the male users
//        $finder->files()->in('src/Tavro/Bundle/CoreBundle/Resources/public/img/avatars/male');
//        $this->createDummyUsers($manager, $finder);

        $manager->flush();

    }

    /**
     * Create "dummy" users for fake data for testing.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     * @param \Symfony\Component\Finder\Finder $finder
     *
     * @throws \Exception
     */
    public function createDummyUsers(ObjectManager $manager, Finder $finder)
    {
        $handler = $this->container->get('tavro.handler.users');
        $imageHandler = $this->container->get('tavro.handler.images');

        foreach ($finder as $file) {

            $data = array(
                'password'  => 'Password1!',
                'username'  => uniqid(),
                'email'     => sprintf('%s@tavrohub.dev', uniqid())
            );

            $user = $handler->create($data);

            $filename = sprintf('%s-%s.%s', $user->getId(), uniqid(), $file->getExtension());

            $data = array(
                'Bucket'        => $this->container->getParameter('image_bucket'),
                'Key'           => $filename,
                'Body'          => $file->getContents(),
                'ContentType'   => sprintf('image/%s', $file->getExtension()),
                'ContentLength' => $file->getSize(),
                'ACL'           => 'public-read',
                'params'        => array(
                    'filesize'              => $file->getSize(),
                    'mime_type'             => sprintf('image/%s', $file->getExtension()),
                    'original_filename'     => $file->getBasename(),
                    'height'                => 0,
                    'width'                 => 0,
            ));

            $avatar = $imageHandler->move($data, $filename, 'avatars');

            $user->setAvatar($avatar);
            $manager->persist($user);

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