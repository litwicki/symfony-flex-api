<?php

namespace App\Controller\Entity;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Exception\Api\ApiException;
use App\Exception\Api\ApiNotFoundException;
use App\Exception\Api\ApiRequestLimitException;
use App\Exception\Api\ApiAccessDeniedException;
use App\Exception\Form\InvalidFormException;
use App\Exception\Entity\Account\AccountStatusDisabledException;
use App\Exception\Entity\Account\AccountStatusPendingException;
use App\Exception\Entity\Account\AccountStatusOtherException;
use App\Exception\Entity\Account\AccountStatusInvalidException;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use App\Entity\Expense;
use App\Entity\ExpenseComment;
use Symfony\Component\HttpFoundation\Cookie;
use App\Entity\Account;

use Litwicki\Common\Common;
use App\Controller\Api\EntityApiController;

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
