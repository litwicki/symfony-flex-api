<?php

namespace App\Controller\AccountEntity;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Account;

use Litwicki\Common\Common;
use App\Controller\Api\AccountEntityApiController;

class ServiceController extends AccountEntityApiController
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

            $data = $account->getServices();

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