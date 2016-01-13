<?php

namespace Tavro\Bundle\ApiBundle\Handler;

use Tavro\Bundle\ApiBundle\Model\OwnershipHandlerInterface;
use Tavro\Bundle\ApiBundle\Services\OrganizationEntityHandler;
use Tavro\Bundle\ApiBundle\Exception\ApiException;
use Tavro\Bundle\ApiBundle\Exception\ApiNotFoundException;
use Tavro\Bundle\ApiBundle\Exception\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;
use Tavro\Bundle\CoreBundle\Model\ApiEntityInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\HttpFoundation\Request;
use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Entity\Node;
use Tavro\Bundle\CoreBundle\Entity\NodeRead;
use Tavro\Bundle\CoreBundle\Entity\NodeTag;
use Tavro\Bundle\CoreBundle\Entity\Tag;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class NodeHandler
 *
 * @package Tavro\Bundle\ApiBundle\Handler
 */
class NodeHandler extends OrganizationEntityHandler implements OwnershipHandlerInterface
{

    /**
     * @param array $parameters
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface|mixed
     * @throws \Exception
     */
    public function create(Request $request, array $parameters)
    {
        try {

            if(!isset($parameters['status'])) {

                if($this->isModerator || $this->isAdmin) {
                    $parameters['status'] = $this->container->getParameter('status_active');
                }
                else {
                    $parameters['status'] = $this->container->getParameter('status_pending');
                }

            }

            if(!isset($parameters['display_date'])) {
                $parameters['display_date'] = new \DateTime();
            }

            $entity = $this->createEntity();
            $entity = $this->processForm($request, $entity, $parameters, 'POST');

            if(isset($parameters['node_tags'])) {
                $tags = $parameters['node_tags'];
                unset($parameters['node_tags']);
                $this->processNodeTags($entity, $tags);
            }

            /**
             * If this is an ApiEntity immediately save so the slug property
             * is updated correctly with the entity Id: {id}-{url-save-title}
             */
            if($entity instanceof ApiEntityInterface) {
                return $this->put($request, $entity, $parameters);
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
     * @param \Tavro\Bundle\CoreBundle\Entity\Node $node
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
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
     * @param \Tavro\Bundle\CoreBundle\Entity\Node $node
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
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
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $entity
     * @param array $parameters
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface
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

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param array $parameters
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $entity
     *
     * @throws \ApiException
     * @throws \Exception
     */
    public function validate(EntityInterface $entity, array $parameters)
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
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $entity
     *
     * @throws \Exception
     */
    public function validateSlug($slug, EntityInterface $entity)
    {
        try {

            if(!preg_match('/[a-z0-9\-]/', $slug)) {
                throw new ApiException('Only alpha-numeric and "-" characters are allowed.');
            }

            $node = $this->repository->findOneBy(array(
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
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     *
     * @return mixed|void
     * @throws \Tavro\Bundle\ApiBundle\Exception\ApiException
     */
    public function findByUser(User $user = null)
    {
        try {

            $entities = $this->repository->findBy(array(
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

            $entity = $this->repository->findOneBy(array(
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
            $options = array('status', 'slug', 'type');

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
     * Find all Entities (limit the response size)
     * Optionally page the result set by LIMIT and OFFSET.
     *
     * @param array $params
     *
     * @throws \Tavro\Bundle\ApiBundle\Exception\ApiAccessDeniedException
     * @throws \Exception
     * @return array|void
     */
    public function findAll(array $params = null)
    {
        try {

            $page = isset($params['page']) ? $params['page'] : 1;
            $size = isset($params['size']) ? $params['size'] : $this->pageSize;

            $sort = (isset($params['sort'])) ? $params['sort'] : 'desc';
            $orderBy = (isset($params['orderBy'])) ? $params['orderBy'] : 'views';

            $sortOrder = array($orderBy => $sort);

//            if(!isset($params['status'])) {
//                $params['status'] = $this->statusActive; //@TODO: Make this a constant fetched from Model\Entity.php
//            }

            $offset = ($page - 1) * $size;

            $params = $this->filterParams($params);

            $entities = $this->repository->findBy(
                $params,
                $sortOrder,
                $size,
                $offset
            );

            $items = array();

            foreach($entities as $entity) {
                if($this->auth->isGranted('view', $entity)) {
                    $items[] = $entity;
                }
            }

            return $items;

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $entity
     * @param array $parameters
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface|mixed
     * @throws \Exception
     */
    public function put(EntityInterface $entity, array $parameters)
    {
        try {

            if(isset($parameters['node_tags'])) {
                $tags = $parameters['node_tags'];
                unset($parameters['node_tags']);
                $this->processNodeTags($entity, $tags);
            }

            $this->validate($entity, $parameters);
            return $this->processForm($entity, $parameters, 'PUT');

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $entity
     * @param array $tags
     *
     * @throws \Exception
     */
    public function processNodeTags(EntityInterface $entity, array $tags)
    {

        try {

            /**
             * First, delete all existing Node Tags so we can do this super fast.
             */
            $query = $this->om->createQuery('DELETE TavroCoreBundle:NodeTag nt WHERE nt.node=:node');

            $query->setParameter('node', $entity->getId());
            $query->getResult();

            if(!empty($tags)) {

                foreach($tags as $tag) {

                    /**
                     * If it's a new tag, then we should first fetch the Tag entity by this name, and/or create it.
                     */
                    $_tag = $this->om->getRepository('TavroCoreBundle:Tag')->findOneBy(array(
                        'title' => $tag['title']
                    ));

                    if(!$_tag instanceof Tag) {
                        $_tag = $this->container->get('tavro.handler.tags')->create($tag);
                    }

                    $nt = new NodeTag();
                    $nt->setNode($entity);
                    $nt->setTag($_tag);
                    $this->om->persist($nt);

                }

                $this->om->flush();

            }

        }
        catch(\Exception $e) {
            throw $e;
        }

    }

}