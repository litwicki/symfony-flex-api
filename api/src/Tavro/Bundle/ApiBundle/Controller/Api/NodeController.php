<?php

namespace Tavro\Bundle\ApiBundle\Controller\Api;

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
use Tavro\Bundle\CoreBundle\Entity\Tag;
use Tavro\Bundle\CoreBundle\Entity\NodeTag;
use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Entity\NodeComment;
use Symfony\Component\HttpFoundation\Cookie;

use Litwicki\Common\Common;
use Tavro\Bundle\ApiBundle\Controller\Api\ApiController as ApiController;

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

            return $this->apiResponse($entities, [
                'format' => $_format,
                'group' => 'simple'
            ]);

        }
        catch(\Exception $e) {
            throw $e;
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

            $data = json_decode($request->getContent(), TRUE);

            $handler = $this->getHandler('comments');
            $comment = $handler->post($request, $data);

            /**
             * Attach the Comment to the Node
             */
            $this->getHandler('node_comments')->post($request, array(
                'comment' => $comment->getId(),
                'node' => $node->getId()
            ));

            $routeOptions = array(
                'entity'  => 'comments',
                'id'      => $comment->getId(),
                'format'  => $_format,
            );

            return $this->forward('TavroApiBundle:Default:get', $routeOptions);

        }
        catch(\Exception $e) {
            throw $e;
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

            return $this->apiResponse($entities, [
                'format' => $_format,
                'group' => 'simple'
            ]);

        }
        catch(\Exception $e) {
            throw $e;
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

            $data = json_decode($request->getContent(), TRUE);

            $handler = $this->getHandler('tags');
            $tag = $handler->post($request, $data);

            /**
             * Attach the Comment to the Node
             */
            $this->getHandler('node_tags')->post($request, array(
                'comment' => $tag->getId(),
                'node' => $node->getId()
            ));

            return $this->delete($request, 'tags', $tag->getId(), $_format);

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete a Tag from a Node, but not physically itself.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Node $node
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function deleteTagAction(Request $request, Node $node, Tag $tag, $_format)
    {
        try {

            $entity = $this->getDoctrine()->getManager()->getRepository('TavroApiBundle:NodeTag')->findOneBy(array(
                'node' => $node,
                'tag' => $tag,
            ));

            if(!$entity instanceof NodeTag) {
                throw new ApiException('There is no Tag to delete for this Node.');
            }

            return $this->deleteAction($request, 'node_tags', $entity->getId(), $_format);

        }
        catch(\Exception $e) {
            throw $e;
        }

    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function byUserAction(Request $request, User $user, $_format)
    {
        $nodes = $user->getNodes();

        return $this->apiResponse($nodes, [
            'format' => $_format,
            'group' => 'simple'
        ]);
    }

}