<?php

namespace Tavro\Bundle\ApiBundle\Controller\Entity;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Tavro\Bundle\CoreBundle\Entity\Account;

use Litwicki\Common\Common;
use Tavro\Bundle\ApiBundle\Controller\Api\ApiController as ApiController;

class ServiceController extends ApiController
{
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
        try {

            $entities = $account->getServices();

            return $this->apiResponse($entities, [
                'format' => $_format,
                'group' => 'simple'
            ]);
        }
        catch(\Exception $e) {
            throw $e;
        }
    }
}