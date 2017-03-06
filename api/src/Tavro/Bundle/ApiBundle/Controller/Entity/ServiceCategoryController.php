<?php

namespace Tavro\Bundle\ApiBundle\Controller\Entity;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tavro\Bundle\CoreBundle\Entity\Account;

use Tavro\Bundle\ApiBundle\Controller\Api\ApiController as ApiController;

class ServiceCategoryController extends ApiController
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
        $data = null;

        try {

            $data = $account->getServiceCategories();

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