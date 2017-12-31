<?php

namespace App\Controller\AccountEntity;

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
use App\Controller\Api\AccountEntityApiController;

class AccountUserController extends AccountEntityApiController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Account $account
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