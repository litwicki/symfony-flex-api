<?php

namespace App\Controller\Entity;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Exception\Api\ApiException;
use App\Exception\Api\ApiNotFoundException;
use App\Exception\Api\ApiRequestLimitException;
use App\Exception\Api\ApiAccessDeniedException;
use App\Exception\Form\InvalidFormException;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use App\Entity\Account;
use App\Entity\Expense;
use App\Entity\ExpenseComment;
use Symfony\Component\HttpFoundation\Cookie;

use Litwicki\Common\Common;
use App\Controller\Api\EntityApiController;

class ShareholderController extends EntityApiController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Account $account
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function byAccountAction(Request $request, Account $account, $_format)
    {
        $data = null;

        try {

            $data = $account->getShareholders();

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