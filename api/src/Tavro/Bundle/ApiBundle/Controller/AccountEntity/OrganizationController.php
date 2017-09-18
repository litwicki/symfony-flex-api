<?php

namespace Tavro\Bundle\ApiBundle\Controller\AccountEntity;

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
use Tavro\Bundle\ApiBundle\Controller\Api\AccountEntityApiController;

class OrganizationController extends AccountEntityApiController
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
        $data = null;

        try {

            $data = $organization->getContacts();

            $options = [
                'code' => Response::HTTP_OK,
                'format' => $_format
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
        $data = null;

        try {

            $handler = $this->getHandler('organizations');
            $data = $handler->getComments($organization);

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
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function newCommentAction(Request $request, Organization $organization, $_format)
    {
        try {

            $data = $this->getPayload($request);

            $handler = $this->getHandler('comments');
            $comment = $handler->post($request, $data);

            /**
             * Attach the Comment to the Organization
             */
            $this->getHandler('organization_comments')->post($request, array(
                'comment' => $comment->getId(),
                'organization' => $organization->getId()
            ));

            $options = [
                'format' => $_format,
                'code' => Response::HTTP_CREATED,
                'message' => sprintf('Comment %s submitted to Organization %s', $comment->getId(), $organization->getId())
            ];

            $data = $comment;

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
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function byAccountAction(Request $request, Account $account, $_format)
    {
        $data = null;

        try {

            if(false === $this->isGranted('view', $account)) {
                throw new ApiAccessDeniedException('You are not authorized to access this Account.');
            }

            $data = $account->getOrganizations();

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