<?php

namespace Tavro\Bundle\ApiBundle\Handler;

use Tavro\Bundle\CoreBundle\Model\EntityInterface;
use Tavro\Bundle\ApiBundle\Services\OwnershipEntityHandler;
use Tavro\Bundle\ApiBundle\Exception\ApiAccessDeniedException;
use Tavro\Bundle\ApiBundle\Exception\ApiException;

use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Entity\Comment;
use Tavro\Bundle\CoreBundle\Entity\NodeComment;
use Tavro\Bundle\CoreBundle\Entity\ModComment;
use Tavro\Bundle\CoreBundle\Entity\Node;
use Tavro\Bundle\CoreBundle\Entity\Mod;

/**
 * Class CommentHandler
 *
 * @package Tavro\Bundle\ApiBundle\Handler
 */
class CommentHandler extends OwnershipEntityHandler
{
    /**
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface $entity
     * @param array $parameters
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface
     * @throws \Exception
     */
    public function patch(EntityInterface $entity, array $parameters)
    {
        try {

            if(!$this->auth->isGranted('patch', $entity)) {
                $message = sprintf('Unable to properly "patch" %s: %s', get_class($entity), $entity->__toString());
                throw new ApiAccessDeniedException($message);
            }

            /**
             * If the status is being set to 0 (deleted) then also set
             * the removed_by to the current user.
             */
            if($parameters['status'] == 0) {
                $parameters['removed_by'] = $this->container->get('tavro.handler.users')->find($parameters['removed_by']);
            }

            return $this->applyPatch($entity, $parameters);

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
     * @throws \Exception
     */
    public function filterParams(array $params)
    {
        try {

            $parameters = array();
            $options = array('status', 'mod', 'node');

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
     * @return array|void
     */
    public function findAll(array $params = null)
    {
        try {

            $page = isset($params['page']) ? $params['page'] : 1;
            $size = isset($params['size']) ? $params['size'] : $this->pageSize;

            $sort = (isset($params['sort'])) ? $params['sort'] : 'desc';
            $orderBy = (isset($params['orderBy'])) ? $params['orderBy'] : 'create_date';

            $sortOrder = array($orderBy => $sort);

            if(!isset($params['status'])) {
                $params['status'] = $this->statusActive; //@TODO: Make this a constant fetched from Model\Entity.php
            }

            $offset = ($page - 1) * $size;

            $params = $this->filterParams($params);

            if(isset($params['mod'])) {
                $repository = $this->om->getRepository('TavroCoreBundle:ModComment');
            }
            elseif(isset($params['node'])) {
                $repository = $this->om->getRepository('TavroCoreBundle:NodeComment');
            }
            else {
                $repository = $this->om->getRepository('TavroCoreBundle:Comment');
            }

            $entities = $repository->findBy(
                $params,
                $sortOrder,
                $size,
                $offset
            );

            $items = array();

            foreach($entities as $entity) {
                $comment = $entity->getComment();
                if($this->auth->isGranted('view', $comment)) {
                    if($comment->getStatus() == 0) {
                        $items[] = array(
                            'id' => $comment->getId(),
                            'status' => $comment->getStatus(),
                            'user' => $comment->getUser(),
                            'removed_by' => array(
                                'id' => $comment->getRemovedBy()->getId(),
                                'username' => $comment->getRemovedBy()->getUsername()
                            ),
                            'create_date' => $comment->getCreateDate(),
                            'update_date' => $comment->getUpdateDate(),
                        );
                    }
                    else {
                        $items[] = $comment;
                    }
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
     * Dynamically create a Comment for either a Node or a Mod.
     *
     * @param array $parameters
     *
     * @return \Tavro\Bundle\CoreBundle\Model\EntityInterface|mixed
     */
    public function create(array $parameters)
    {
        try {

            if(isset($parameters['mod'])) {
                $mod = $this->om->getRepository('TavroCoreBundle:Mod')->find($parameters['mod']);
                unset($parameters['mod']);
            }

            if(isset($parameters['node'])) {
                $node = $this->om->getRepository('TavroCoreBundle:Node')->find($parameters['node']);
                unset($parameters['node']);
            }

            $entity = $this->createEntity();
            $comment = $this->processForm($entity, $parameters, 'POST');

            if(isset($mod) && $mod instanceof Mod) {
                $this->createModComment($mod, $comment);
            }

            if(isset($node) && $node instanceof Node) {
                $this->createNodeComment($node, $comment);
            }

            return $comment;

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\Mod $mod
     * @param \Tavro\Bundle\ApiBundle\Handler\Comment $comment
     *
     * @throws \Exception
     */
    public function createModComment(Mod $mod, Comment $comment)
    {
        try {
            $mc = new ModComment();
            $mc->setComment($comment);
            $mc->setMod($mod);
            $this->om->persist($mc);
            $this->om->flush();
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\Node $node
     * @param \Tavro\Bundle\ApiBundle\Handler\Comment $comment
     *
     * @throws \Exception
     */
    public function createNodeComment(Node $node, Comment $comment)
    {
        try {
            $mc = new NodeComment();
            $mc->setComment($comment);
            $mc->setNode($node);
            $this->om->persist($mc);
            $this->om->flush();
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

}