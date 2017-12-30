<?php

namespace Tavro\Controller\Entity;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Tavro\Exception\Api\ApiException;
use Tavro\Exception\Api\ApiNotFoundException;
use Tavro\Exception\Api\ApiRequestLimitException;
use Tavro\Exception\Api\ApiAccessDeniedException;
use Tavro\Exception\Form\InvalidFormException;
use Tavro\Exception\Entity\Account\AccountStatusDisabledException;
use Tavro\Exception\Entity\Account\AccountStatusPendingException;
use Tavro\Exception\Entity\Account\AccountStatusOtherException;
use Tavro\Exception\Entity\Account\AccountStatusInvalidException;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Tavro\Entity\Expense;
use Tavro\Entity\ExpenseComment;
use Symfony\Component\HttpFoundation\Cookie;
use Tavro\Entity\Account;

use Litwicki\Common\Common;
use Tavro\Controller\Api\EntityApiController;

class AccountController extends EntityApiController
{

    /**
     * Get (find) an Account by Id.
     *
     * @param $entity
     * @param $id
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function getSubscriptionAction($entity, $id, $_format)
    {
        $data = null;

        try {

            $handler = $this->getHandler($entity);
            $account = $handler->get($id);

            /**
             * Once we have fetched the Account
             */
            $chargify = $this->get('chargify.handler.subscription');

            $subscriptionId = 0;

            /**
             * @TODO: get the subscription id from the customer or account
             */

            $data = $chargify->get($subscriptionId);

            $options = [
                'format' => $_format
            ];

        }
        catch(\Exception $e) {
            $options = $this->getExceptionOptions($e, $_format);
        }

        return $this->apiResponse($data, $options);
    }

}
