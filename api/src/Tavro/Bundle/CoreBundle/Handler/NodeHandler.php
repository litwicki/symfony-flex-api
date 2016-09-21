<?php

namespace Tavro\Bundle\CoreBundle\Handler;

use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Model\EntityHandler;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiNotFoundException;
use Tavro\Bundle\CoreBundle\Exception\UsernameNotUniqueException;
use Tavro\Bundle\CoreBundle\Exception\EmailNotUniqueException;

use Rhumsaa\Uuid\Uuid;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Tavro\Bundle\CoreBundle\Exception\InvalidUsernameException;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Tavro\Bundle\CoreBundle\Entity\Node;
use Tavro\Bundle\CoreBundle\Entity\NodeTag;
use Tavro\Bundle\CoreBundle\Entity\NodeRead;
use Tavro\Bundle\CoreBundle\Entity\User;

/**
 * Class NodeHandler
 *
 * @package Tavro\Bundle\CoreBundle\Handler
 */
class NodeHandler extends EntityHandler
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $parameters
     *
     * @return object|void
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
             * If this is an ApiEntity immediately save so the slug property
             * is updated correctly with the entity Id: {id}-{url-save-title}
             */
            if($entity instanceof EntityInterface) {
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $entity
     * @param array $parameters
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface|void
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
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $entity
     * @param array $parameters
     *
     * @return bool|void
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
     * @throws \Tavro\Bundle\CoreBundle\Exception\Api\ApiException
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
     * Find all Entities (limit the response size)
     * Optionally page the result set by LIMIT and OFFSET.
     *
     * @param array $params
     *
     * @throws \Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException
     * @throws \Exception
     * @return array|void
     */
    public function findAll(array $params = array())
    {
        try {

            $organizations = $this->getMyAccounts();

            $page = isset($params['page']) ? $params['page'] : 1;
            $size = isset($params['size']) ? $params['size'] : self::PAGE_SIZE;

            $sort = (isset($params['sort'])) ? $params['sort'] : 'desc';
            $orderBy = (isset($params['orderBy'])) ? $params['orderBy'] : 'id';

            $sortOrder = array($orderBy => $sort);

            $offset = ($page - 1) * $size;

            $params = $this->filterParams($params);

            //default the status to ACTIVE
            if(!isset($params['status'])) {
                $params['status'] = self::STATUS_ACTIVE;
            }

            $entities = $this->repository->findAllByOrganization($organizations, $size, $offset, $params);

            $items = array();
            $count = 0;

            foreach($entities as $entity) {
                if($this->auth->isGranted('view', $entity)) {
                    $id = $entity->getOrganization()->getId();
                    $items[$id][] = $entity;
                    $count++;
                }
            }

            return array(
                'data' => $items,
                'message' => sprintf('%s Nodes retrieved.', $count),
            );

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $entity
     * @param array $parameters
     *
     * @throws \Exception
     */
    public function put(Request $request, EntityInterface $entity, array $parameters)
    {
        try {
            $this->validate($entity, $parameters);
            return $this->processForm($request, $entity, $parameters, $this::HTTP_METHOD_PUT);
        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

}