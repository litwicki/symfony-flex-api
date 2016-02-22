<?php

namespace Tavro\Bundle\CoreBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiNotFoundException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiRequestLimitException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Tavro\Bundle\CoreBundle\Entity\Node;
use Tavro\Bundle\CoreBundle\Entity\NodeComment;
use Symfony\Component\HttpFoundation\Cookie;

use Litwicki\Common\Common;
use Tavro\Bundle\CoreBundle\Controller\Api\DefaultController as ApiController;

class NodeController extends ApiController
{

    /**
     * Display all Comments for this Node.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Node $node
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function commentsAction(Request $request, Node $node, $_format)
    {
        try {

            $entities = $node->getNodeComments();

            $items = array();

            foreach($entities as $entity) {
                $items[] = $entity->getComment();
            }

        }
        catch(\Exception $e) {
            throw $e;
        }
        finally {
            $data = $this->serialize($items, $_format, $group = 'simple');
            $response = $this->apiResponse($data, $_format);
            return $response;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Node $node
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function newCommentAction(Request $request, Node $node, $_format)
    {
        try {

            $data = json_decode($request->getContent(), true);

            $handler = $this->getHandler('comment');
            $comment = $handler->post($request, $data);

            /**
             * Attach the Comment to the Node
             */
            $this->getHandler('node_comment')->post($request, array(
                'comment' => $comment->getId(),
                'node' => $node->getId()
            ));

        }
        catch(\Exception $e) {
            throw $e;
        }
        finally {
            $routeOptions = array(
                'entity'  => 'comment',
                'id'      => $comment->getId(),
                'format'  => $_format,
            );

            return $this->forward('TavroCoreBundle:Default:get', $routeOptions);
        }
    }

    /**
     * Display all Tags for this Node.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Node $node
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function tagsAction(Request $request, Node $node, $_format)
    {
        try {

            $entities = $node->getNodeTags();

            $items = array();

            foreach($entities as $entity) {
                $items[] = $entity->getTag();
            }

        }
        catch(\Exception $e) {
            throw $e;
        }
        finally {
            $data = $this->serialize($items, $_format, $group = 'simple');
            $response = $this->apiResponse($data, $_format);
            return $response;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Node $node
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function newTagAction(Request $request, Node $node, $_format)
    {
        try {

            $data = json_decode($request->getContent(), true);

            $handler = $this->getHandler('tags');
            $tag = $handler->post($request, $data);

            /**
             * Attach the Comment to the Node
             */
            $this->getHandler('node_tag')->post($request, array(
                'comment' => $tag->getId(),
                'node' => $node->getId()
            ));

        }
        catch(\Exception $e) {
            throw $e;
        }
        finally {
            $routeOptions = array(
                'entity'  => 'comment',
                'id'      => $tag->getId(),
                'format'  => $_format,
            );

            return $this->forward('TavroCoreBundle:Default:get', $routeOptions);
        }
    }

}