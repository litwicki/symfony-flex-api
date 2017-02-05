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

use Tavro\Bundle\CoreBundle\Entity\Account;
use Tavro\Bundle\CoreBundle\Entity\Organization;
use Symfony\Component\HttpFoundation\Cookie;

use Litwicki\Common\Common;
use Tavro\Bundle\ApiBundle\Controller\Api\ApiController as ApiController;

class OrganizationController extends ApiController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function contactsAction(Request $request, Organization $organization, $_format)
    {
        try {

            $entities = $organization->getContacts();

            return $this->apiResponse($entities, [
                'format' => $_format,
            ]);

        }
        catch(\Exception $e) {
            throw $e;
        }

    }
    
    /**
     * Display all Comments for this Organization.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function commentsAction(Request $request, Organization $organization, $_format)
    {
        try {

            $entities = $organization->getOrganizationComments();

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
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function newCommentAction(Request $request, Organization $organization, $_format)
    {
        try {

            $data = json_decode($request->getContent(), TRUE);

            $handler = $this->getHandler('comments');
            $comment = $handler->post($request, $data);

            /**
             * Attach the Comment to the Organization
             */
            $this->getHandler('organization_comments')->post($request, array(
                'comment' => $comment->getId(),
                'organization' => $organization->getId()
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function byAccount(Request $request, Account $account, $_format)
    {
        try {

            if(false === $this->isGranted('view', $account)) {
                throw new ApiAccessDeniedException('You are not authorized to access this Account.');
            }

            $entities = $account->getOrganizations();

            return $this->apiResponse($entities, [
                'format' => $_format,
                'group' => 'simple'
            ]);
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

}