<?php

namespace App\Handler\AccountEntity;

use App\Exception\Api\ApiException;
use App\Handler\EntityHandler;
use App\Exception\Form\InvalidFormException;
use App\Model\EntityInterface\EntityInterface;
use App\Exception\Api\ApiAccessDeniedException;
use App\Exception\Api\ApiNotFoundException;
use App\Exception\UsernameNotUniqueException;
use App\Exception\EmailNotUniqueException;

use Rhumsaa\Uuid\Uuid;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use App\Exception\InvalidUsernameException;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Tools\Pagination\Paginator;

use App\Entity\Node;
use App\Entity\NodeTag;
use App\Entity\NodeRead;
use App\Entity\User;

use App\Handler\AccountEntityHandler;
use App\Model\HandlerInterface\AccountEntityHandlerInterface;
use App\Model\HandlerInterface\CommentEntityHandlerInterface;
use App\Model\HandlerInterface\TagEntityHandlerInterface;

/**
 * Class NodeHandler
 *
 * @package Tavro\Handler\Entity
 */
class NodeHandler extends AccountEntityHandler implements CommentEntityHandlerInterface, TagEntityHandlerInterface, AccountEntityHandlerInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $parameters
     *
     * @return \Tavro\Model\EntityInterface\EntityInterface
     * @throws \Exception
     */
    public function create(Request $request, array $parameters)
    {
        try {

            if(!isset($parameters['status'])) {
                $parameters['status'] = self::STATUS_ACTIVE;
            }

            if(!isset($parameters['display_date'])) {
                $parameters['display_date'] = new \DateTime();
            }

            $entity = $this->createEntity();
            $entity = $this->processForm($request, $entity, $parameters, $this::HTTP_METHOD_POST);

            /**
             * To slugify this for the URL, PUT immediately after creating
             * so we can use the generated id and "title" for the slug.
             */
            if($entity instanceof EntityInterface) {
                $entity = $this->put($request, $entity, $parameters);
            }

            return $entity;

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(TransformationFailedException $e) {
            throw $e;
        }
        catch(UnexpectedTypeException $e) {
            throw $e;
        }
        catch(InvalidPropertyPathException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \App\Entity\Node $node
     * @param \App\Entity\User $user
     *
     * @return bool
     * @throws \Exception
     */
    public function userHasRead(Node $node, User $user)
    {
        try {

            $repository = $this->om->getRepository('TavroCoreBundle:NodeRead');

            $nr = $repository->findOneBy(array(
                'user' => $user,
                'node' => $node
            ));

            if($nr instanceof NodeRead) {
                return true;
            }

            return false;

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \App\Entity\Node $node
     * @param \App\Entity\User $user
     *
     * @throws \Exception
     */
    public function readNode(Node $node, User $user)
    {
        try {

            $nr = new NodeRead();
            $nr->setNode($node);
            $nr->setUser($user);
            $this->om->persist($nr);
            $this->om->flush();

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Model\EntityInterface\EntityInterface $entity
     * @param array $parameters
     *
     * @return \Tavro\Model\EntityInterface\EntityInterface
     * @throws \Exception
     */
    public function patch(Request $request, EntityInterface $entity, array $parameters)
    {
        try {

            if(!$this->auth->isGranted('patch', $entity)) {
                $message = sprintf('Unable to properly "patch" %s: %s', get_class($entity), $entity->__toString());
                throw new ApiAccessDeniedException($message);
            }

            $node = $this->applyPatch($request, $entity, $parameters);

            $token = $this->tokenStorage->getToken();

            if($token instanceof UsernamePasswordToken) {

                $user = $token->getUser();

                if($user instanceof User) {
                    if (!$this->userHasread($node, $this->currentUser)) {
                        $this->readNode($node, $this->currentUser);
                    }
                }

            }

            /**
             * @TODO: when do we apply this?
             */
            //$this->dispatchUpdateEvent($entity);

            return $node;

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \App\Model\EntityInterface\EntityInterface $entity
     * @param array $parameters
     *
     * @throws \Exception
     */
    public function validate(EntityInterface $entity, array $parameters = array())
    {
        try {

            foreach($parameters as $key => $value) {

                switch($key) {

                    case 'slug':
                        $this->validateSlug($value, $entity);
                        break;

                }

            }

        }
        catch(ApiException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $slug
     * @param \App\Model\EntityInterface\EntityInterface $entity
     *
     * @throws \Exception
     */
    public function validateSlug($slug, EntityInterface $entity)
    {
        try {

            if(!preg_match('/[a-z0-9\-]/', $slug)) {
                throw new ApiException('Only alpha-numeric and "-" characters are allowed.');
            }

            $node = $this->getRepository()->findOneBy(array(
                'slug' => $slug
            ));

            if($node instanceof Node) {
                if(!is_null($entity) && ($node->getId() !== $entity->getId())) {
                    throw new ApiException(sprintf('"%s" is already in use by another Node!', $slug));
                }
            }

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \App\Entity\User $user
     *
     * @return mixed
     * @throws \Tavro\Exception\Api\ApiException
     */
    public function findByUser(User $user = null)
    {
        try {

            $entities = $this->getRepository()->findBy(array(
                'user' => $user
            ));

            return $entities;

        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * Find a Node by slug.
     *
     * @param $slug
     *
     * @return object
     * @throws \Exception
     */
    public function findBySlug($slug)
    {
        try {

            $entity = $this->getRepository()->findOneBy(array(
                'slug' => $slug
            ));

            if(!$entity instanceof Node) {
                throw new ApiNotFoundException(sprintf('Could not find node with slug "%s"', $slug));
            }

            return $entity;

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Filter out parameters we don't want to give access to.
     *
     * @param array $params
     *
     * @return array
     * @throws \Exception
     */
    public function filterParams(array $params)
    {
        try {

            $parameters = array();
            $options = array('status', 'type');

            foreach($params as $name => $value) {
                if(in_array($name, $options)) {
                    $parameters[$name] = $value;
                }
            }

            return $parameters;

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Model\EntityInterface\EntityInterface $entity
     * @param array $parameters
     *
     * @return \Tavro\Model\EntityInterface\EntityInterface|void
     * @throws \Exception
     */
    public function put(Request $request, EntityInterface $entity, array $parameters)
    {
        try {
            $this->validate($entity, $parameters);
            $this->processForm($request, $entity, $parameters, $this::HTTP_METHOD_PUT);
        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \App\Model\EntityInterface\EntityInterface $node
     *
     * @return array
     * @throws \Exception
     */
    public function getComments(EntityInterface $node)
    {

        $comments = array();

        if(!$node instanceof Node) {
            throw new \Exception(sprintf('Cannot fetch comments for Node from %s', get_class($node)));
        }

        $entities = $node->getNodeComments();

        if(!empty($entities)) {
            foreach($entities as $entity) {
                $comments[$entity->getId()] = $entity->getComment();
            }
        }

        return $comments;

    }

    /**
     * Get All Tags.
     * 
     * @param \App\Model\EntityInterface\EntityInterface $node
     *
     * @return array
     * @throws \Exception
     */
    public function getTags(EntityInterface $node)
    {

        $tags = array();

        if(!$node instanceof Node) {
            throw new \Exception(sprintf('Cannot fetch tags for Node from %s', get_class($node)));
        }

        $entities = $node->getNodeTags();

        if(!empty($entities)) {
            foreach($entities as $entity) {
                $tags[$entity->getId()] = $entity->getTag();
            }
        }

        return $tags;

    }

}