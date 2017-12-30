<?php

namespace Tavro\Controller\AccountEntity;

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
use Tavro\Controller\Api\AccountEntityApiController;

class AccountUserController extends AccountEntityApiController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Entity\Account $account
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function getAll(Request $request, $account, $_format)
    {
        $data = null;

        try {

            $entities = $account->getAccountUsers();

            $data = array();

            foreach($entities as $entity) {
                $data[] = $entity->getUser();
            }

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