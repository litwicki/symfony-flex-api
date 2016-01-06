<?php

namespace Tavro\Bundle\AppBundle\Controller;

use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Tavro\Bundle\ApiBundle\Exception\ApiNotFoundException;
use Doctrine\Common\Util\Inflector;

use Tavro\Bundle\CoreBundle\Entity\Node;

class NodeController extends Controller
{
    /**
     * @return \Tavro\Bundle\ApiBundle\Handler\NodeHandler
     */
    public function getHandler()
    {
        return $this->container->get('tavro.handler.nodes');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function guideAction(Request $request, $slug)
    {
        try {

            $node = $this->getHandler()->findBySlug($slug);

            if($node->getType() !== 'guide') {
                throw new NotFoundHttpException(sprintf('No guide with slug "%s"', $slug));
            }

            return $this->forward('TavroAppBundle:Node:view', array(
                'request' => $request,
                'slug' => $slug
            ));

        }
        catch(ApiNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Node $node
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function guideIdAction(Request $request, Node $node)
    {
        try {
            if($node->getType() !== 'guide') {
                throw new NotFoundHttpException(sprintf('No guide with id "%s"', $node->getId()));
            }

            return $this->forward('TavroAppBundle:Node:view', array(
                'request' => $request,
                'node' => $node
            ));

        }
        catch(ApiNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function articleAction(Request $request, $slug)
    {
        try {

            $node = $this->getHandler()->findBySlug($slug);

            if($node->getType() !== 'article') {
                throw new NotFoundHttpException(sprintf('No article with slug "%s"', $slug));
            }

            return $this->forward('TavroAppBundle:Node:view', array(
                'request' => $request,
                'node' => $node
            ));

        }
        catch(ApiNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Node $node
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function articleIdAction(Request $request, Node $node)
    {
        try {
            if($node->getType() !== 'article') {
                throw new NotFoundHttpException(sprintf('No article with id "%s"', $node->getId()));
            }

            return $this->forward('TavroAppBundle:Node:view', array(
                'request' => $request,
                'node' => $node
            ));

        }
        catch(ApiNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Node $node
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function viewAction(Request $request, Node $node)
    {
        try {

            $page = array(
                'node' => $node,
            );

            return $this->render('TavroAppBundle:Node:view.html.twig', $page);

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * List all Nodes of a given node.type.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $type
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function indexAction(Request $request, $type)
    {
        try {

            $page = array(
                'node_type' => $type,
                'node_type_plural' => Inflector::pluralize($type),
            );

            return $this->render('TavroAppBundle:Node:index.html.twig', $page);

        }
        catch(ApiNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * List all Nodes
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function allAction(Request $request)
    {
        try {
            return $this->render('TavroAppBundle:Node:all.html.twig');
        }
        catch(ApiNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Node $node
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function editAction(Request $request, Node $node)
    {
        try {

            $page = array(
                'node' => $node,
            );

            return $this->render('TavroAppBundle:Node:edit.html.twig', $page);

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        if(false === $this->isGranted('create', new Node())) {
            throw new AccessDeniedException('You are not authorized to create content.');
        }

        return $this->render('TavroAppBundle:Node:new.html.twig');

    }
}