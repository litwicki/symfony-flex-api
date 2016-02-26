<?php

namespace Tavro\Bundle\CoreBundle\Handler\Entity;

use Tavro\Bundle\CoreBundle\Model\EntityInterface;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;

use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Entity\Comment;
use Tavro\Bundle\CoreBundle\Entity\NodeComment;
use Tavro\Bundle\CoreBundle\Entity\Node;
use Tavro\Bundle\CoreBundle\Services\EntityHandler;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class CommentHandler
 *
 * @package Tavro\Bundle\CoreBundle\Handler
 */
class CommentHandler extends EntityHandler
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
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

            return $this->applyPatch($request, $entity, $parameters);

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
            $options = array('status');

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
            $size = isset($params['size']) ? $params['size'] : self::PAGE_SIZE;

            $sort = (isset($params['sort'])) ? $params['sort'] : 'desc';
            $orderBy = (isset($params['orderBy'])) ? $params['orderBy'] : 'create_date';

            $sortOrder = array($orderBy => $sort);

            if(!isset($params['status'])) {
                $params['status'] = self::STATUS_ACTIVE; //@TODO: Make this a constant fetched from Model\Entity.php
            }

            $offset = ($page - 1) * $size;

            $params = $this->filterParams($params);
            $plain = false;

            if(isset($params['node'])) {
                $repository = $this->om->getRepository('TavroCoreBundle:NodeComment');
            }
            elseif(isset($params['expense'])) {
                $repository = $this->om->getRepository('TavroCoreBundle:ExpenseComment');
            }
            elseif(isset($params['funding'])) {
                $repository = $this->om->getRepository('TavroCoreBundle:FundingRoundComment');
            }
            elseif(isset($params['customer'])) {
                $repository = $this->om->getRepository('TavroCoreBundle:CustomerComment');
            }
            elseif(isset($params['revenue'])) {
                $repository = $this->om->getRepository('TavroCoreBundle:RevenueComment');
            }
            else {
                $plain = true;
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

                $comment = ($plain) ? $entity : $entity->getComment();

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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $parameters
     *
     * @return mixed|\Tavro\Bundle\CoreBundle\Model\EntityInterface|void
     * @throws \Exception
     */
    public function create(Request $request, array $parameters)
    {
        try {

            $entity = $this->createEntity();
            $comment = $this->processForm($request, $entity, $parameters, 'POST');

            return $comment;

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

}