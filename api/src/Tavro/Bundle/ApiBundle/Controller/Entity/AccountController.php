<?php

namespace Tavro\Bundle\ApiBundle\Controller\Entity;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiNotFoundException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiRequestLimitException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;
use Tavro\Bundle\CoreBundle\Exception\Entity\Account\AccountStatusDisabledException;
use Tavro\Bundle\CoreBundle\Exception\Entity\Account\AccountStatusPendingException;
use Tavro\Bundle\CoreBundle\Exception\Entity\Account\AccountStatusOtherException;
use Tavro\Bundle\CoreBundle\Exception\Entity\Account\AccountStatusInvalidException;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Tavro\Bundle\CoreBundle\Entity\Expense;
use Tavro\Bundle\CoreBundle\Entity\ExpenseComment;
use Symfony\Component\HttpFoundation\Cookie;
use Tavro\Bundle\CoreBundle\Entity\Account;

use Litwicki\Common\Common;
use Tavro\Bundle\ApiBundle\Controller\Api\ApiController as ApiController;

class AccountController extends ApiController
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
    public function getAction($entity, $id, $_format)
    {
        try {

            $handler = $this->getHandler($entity);
            $account = $handler->get($id);

            /**
             * Once we have fetched the Account
             */
            $chargify = $this->get('chargify.handler.customer');
            $customer = $chargify->findByReference($id);

            $data = array(
                'customer' => $customer,
                'account' => $account
            );

            return $this->apiResponse($data, [
                'format' => $_format
            ]);

        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

}
