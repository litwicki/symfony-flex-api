<?php namespace Tavro\Bundle\ApiBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Tavro\Bundle\CoreBundle\Entity\Account;
use Tavro\Bundle\ApiBundle\Exception\Account\ApiAccountPayloadMismatchException;
use Tavro\Bundle\ApiBundle\Exception\Account\ApiAccountPayloadMissingException;
use Tavro\Bundle\ApiBundle\Controller\Api\ApiController;

class AccountEntityApiController extends ApiController
{
    /**
     * Check the permission on the Account.
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     */
    public function isGrantedAccount(Account $account)
    {
        /**
         * If this User isn't allowed to even VIEW the
         * current (active) Account, then naturally they have no business doing anything here..
         */
        if(false === ($this->isGranted('view', $account))) {
            throw new AccessDeniedHttpException('You are not authorized to this Account.');
        }
    }

    /**
     * Validation on the Account and Entity.
     *  - Does the user have access to this Account?
     *  - Does the payload account_id match the Account provided?
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param array $payload
     */
    public function isGrantedAccountAction(Account $account, array $payload = array())
    {
        /**
         * KEEP THIS CHECK FIRST.
         */
        $this->isGrantedAccount($account);

        /**
         * If the payload is missing the `account` parameter, thou shall not pass!
         */
        if(false === (isset($payload['account']))) {
            throw new ApiAccountPayloadMissingException('Payload must include `account` to complete your request.');
        }

        /**
         * If the payload has an `account_id` parameter, but it doesn't match the account id from the route
         * then naturally there is a glitch in the matrix and we cannot proceed.
         */
        if(true === (isset($payload['account'])) && false === ($account->getId() == $payload['account'])) {
            throw new ApiAccountPayloadMismatchException('Payload account_id must match the current Account.');
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
            $this->isGrantedAccountAction($account, $this->getPayload($request));
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
            $this->isGrantedAccountAction($account, $this->getPayload($request));
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
            $this->isGrantedAccountAction($account, $this->getPayload($request));
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
            $this->isGrantedAccountAction($account, $this->getPayload($request));
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
            $this->isGrantedAccount($account);
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
            $this->isGrantedAccount($account);
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
            $this->isGrantedAccount($account);
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
            $this->isGrantedAccount($account);
            return $this->_delete($request, $entity, $id, $_format);
        }
        catch(AccessDeniedHttpException $e) {
            return $this->apiResponse(null, $this->getExceptionOptions($e));
        }
    }


}