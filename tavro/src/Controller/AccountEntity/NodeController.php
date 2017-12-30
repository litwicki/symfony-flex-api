<?php

namespace Tavro\Controller\AccountEntity;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Tavro\Exception\Api\ApiException;
use Tavro\Exception\Api\ApiNotFoundException;
use Tavro\Exception\Api\ApiRequestLimitException;
use Tavro\Exception\Api\ApiAccessDeniedException;
use Tavro\Exception\Form\InvalidFormException;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Tavro\Entity\Account;
use Tavro\Entity\Node;
use Tavro\Entity\Tag;
use Tavro\Entity\NodeTag;
use Tavro\Entity\User;
use Tavro\Entity\NodeComment;
use Symfony\Component\HttpFoundation\Cookie;

use Litwicki\Common\Common;
use Tavro\Controller\Api\AccountEntityApiController;

class NodeController extends AccountEntityApiController
{

    /**
     * Display all Comments for this Node.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Entity\Node $node
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function commentsAction(Request $request, Node $node, $_format)
    {
        $data = null;

        try {

            $handler = $this->getHandler('nodes');
            $data = $handler->getComments($node);

            $options = [
                'format' => $_format,
                'group' => 'simple'
            ];

        }
        catch(\Exception $e) {
            $options = [
                'format' => $_format,
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }

        return $this->apiResponse($data, $options);

    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Entity\Node $node
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function newCommentAction(Request $request, Node $node, $_format)
    {
        $data = null;

        try {

            $data = $this->getPayload($request);

            $handler = $this->getHandler('comments');
            $comment = $handler->post($request, $data);

            /**
             * Attach the Comment to the Node
             */
            $this->getHandler('node_comments')->post($request, array(
                'comment' => $comment->getId(),
                'node' => $node->getId()
            ));

            $data = $comment;

            $options = [
                'code' => Response::HTTP_CREATED,
                'message' => sprintf('Comment %s submitted to Node %s', $comment->getId(), $node->getId())
            ];

        }
        catch(\Exception $e) {
            $options = $this->getExceptionOptions($e, $_format);
        }

        return $this->apiResponse($data, $options);

    }

    /**
     * Display all Tags for this Node.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Entity\Node $node
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function tagsAction(Request $request, Node $node, $_format)
    {
        $data = null;

        try {

            $handler = $this->getHandler('nodes');
            $data = $handler->getTags($node);

            $options = [
                'format' => $_format,
            ];

        }
        catch(\Exception $e) {
            $options = [
                'format' => $_format,
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }

        return $this->apiResponse($data, $options);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Entity\Node $node
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function newTagAction(Request $request, Node $node, $_format)
    {
        $data = null;

        try {

            $data = $this->getPayload($request);

            $handler = $this->getHandler('tags');
            $tag = $handler->post($request, $data);

            /**
             * Attach the Comment to the Node
             */
            $this->getHandler('node_tags')->post($request, array(
                'comment' => $tag->getId(),
                'node' => $node->getId()
            ));

            $data = $tag;

            $options = [
                'format' => $_format,
                'code' => Response::HTTP_CREATED,
                'message' => sprintf('Tag %s submitted to Node %s', $tag->getId(), $node->getId())
            ];

        }
        catch(\Exception $e) {
            $options = $this->getExceptionOptions($e, $_format);
        }

        return $this->apiResponse($data, $options);
    }

    /**
     * Delete a Tag from a Node, but not physically itself.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Entity\Node $node
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function deleteTagAction(Request $request, Node $node, Tag $tag, $_format)
    {
        $data = null;

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
            $options = $this->getExceptionOptions($e, $_format);
        }

        return $this->apiResponse($data, $options);

    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Entity\User $user
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function byUserAction(Request $request, User $user, $_format)
    {
        $data = null;

        try {

            $data = $user->getNodes();

            $options = [
                'format' => $_format,
                'group' => 'simple'
            ];

        }
        catch(\Exception $e) {
            $options = $this->getExceptionOptions($e, $_format);
        }

        return $this->apiResponse($data, $options);
    }

}