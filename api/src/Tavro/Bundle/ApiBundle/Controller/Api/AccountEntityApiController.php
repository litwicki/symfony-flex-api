<?php namespace Tavro\Bundle\ApiBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Tavro\Bundle\CoreBundle\Entity\Account;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiNotFoundException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiRequestLimitException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Tavro\Bundle\CoreBundle\Model\EntityInterface\AccountEntityInterface;

use Doctrine\Common\Inflector\Inflector;

use Litwicki\Common\Common;
use Tavro\Bundle\ApiBundle\Controller\Api\ApiController;

class AccountEntityApiController extends ApiController
{
    /**
     * Validation on the Account and Entity.
     *  - Does the entity in question belong to the Account?
     *  - Does the user have access to this Account?
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param \Tavro\Bundle\CoreBundle\Model\EntityInterface\AccountEntityInterface $entity
     */
    public function checkAccount(Account $account, AccountEntityInterface $entity = null)
    {
//        if(!is_null($entity)) {
//            if (false === ($entity->getAccount()->getId() === $account->getId())) {
//                throw new AccessDeniedHttpException(sprintf('%s does not belong to %s.', get_class($entity), $account->__toString()));
//            }
//        }

        if(false === ($this->isGranted('view', $account))) {
            throw new AccessDeniedHttpException('You are not authorized to this Account.');
        }

    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param $entity
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postAction(Request $request, Account $account, $entity, $_format)
    {
        try {
            $this->checkAccount($account);
            return $this->_post($request, $entity, $_format);
        }
        catch(AccessDeniedHttpException $e) {
            return $this->apiResponse(null, $this->getExceptionOptions($e));
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param $entity
     * @param $id
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putAction(Request $request, Account $account, $entity, $id, $_format)
    {
        try {
            $this->checkAccount($account);
            return $this->_put($request, $entity, $id, $_format);
        }
        catch(AccessDeniedHttpException $e) {
            return $this->apiResponse(null, $this->getExceptionOptions($e));
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param $entity
     * @param $id
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function patchAction(Request $request, Account $account, $entity, $id, $_format)
    {
        try {
            $this->checkAccount($account);
            return $this->_patch($request, $entity, $id, $_format);
        }
        catch(AccessDeniedHttpException $e) {
            return $this->apiResponse(null, $this->getExceptionOptions($e));
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param $entity
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function typeaheadAction(Request $request, Account $account, $entity, $_format)
    {
        try {
            $this->checkAccount($account);
            return $this->_typeahead($request, $entity, $_format);
        }
        catch(AccessDeniedHttpException $e) {
            return $this->apiResponse(null, $this->getExceptionOptions($e));
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param $entity
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAllAction(Request $request, Account $account, $entity, $_format)
    {
        try {
            $this->checkAccount($account);
            return $this->_getAll($request, $entity, $_format);
        }
        catch(AccessDeniedHttpException $e) {
            return $this->apiResponse(null, $this->getExceptionOptions($e));
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param $entity
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAllByAccountAction(Request $request, Account $account, $entity, $_format)
    {
        try {
            $this->checkAccount($account);
            return $this->_getAllByACcount($request, $account, $entity, $_format);
        }
        catch(AccessDeniedHttpException $e) {
            return $this->apiResponse(null, $this->getExceptionOptions($e));
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param $entity
     * @param $id
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction(Request $request, Account $account, $entity, $id, $_format)
    {
        try {
            $this->checkAccount($account);
            return $this->_get($request, $entity, $id, $_format);
        }
        catch(AccessDeniedHttpException $e) {
            return $this->apiResponse(null, $this->getExceptionOptions($e));
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param $entity
     * @param $id
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request, Account $account, $entity, $id, $_format)
    {
        try {
            $this->checkAccount($account);
            return $this->_delete($request, $entity, $id, $_format);
        }
        catch(AccessDeniedHttpException $e) {
            return $this->apiResponse(null, $this->getExceptionOptions($e));
        }
    }


}