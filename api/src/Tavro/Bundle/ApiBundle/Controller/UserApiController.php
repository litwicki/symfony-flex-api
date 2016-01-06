<?php

namespace Tavro\Bundle\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Tavro\Bundle\ApiBundle\Exception\ApiException;
use Tavro\Bundle\ApiBundle\Exception\ApiNotFoundException;
use Tavro\Bundle\ApiBundle\Exception\ApiRequestLimitException;
use Tavro\Bundle\ApiBundle\Exception\ApiAccessDeniedException;

use Doctrine\Common\Inflector\Inflector;

use Tavro\Bundle\CoreBundle\Entity\User;
use Symfony\Component\HttpFoundation\Cookie;

use Tavro\Bundle\ApiBundle\Controller\DefaultController;

use Litwicki\Common\Common;

class UserApiController extends ApiController
{
    /**
     * @param Request $request
     * @param User $user
     * @param $_format
     * @return Response
     */
    public function resetApiKeyAction(Request $request, User $user, $_format)
    {
        try {
            $this->getHandler('users')->resetApiKey($user);
            $cookie = new Cookie('api_key', $user->getApiKey(), 0, '/', NULL, false, false);
            $data = $this->serialize($user, $_format);
            $response = $this->apiResponse($data, $_format);
            $response->headers->setCookie($cookie);
            $this->container->get('tavro.handler.users')->reauthenticate($user);
            return $response;
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param User $user
     * @param $_format
     * @return Response
     */
    public function resetApiPasswordAction(Request $request, User $user, $_format)
    {
        try {
            $this->getHandler('users')->resetApiPassword($user);
            $cookie = new Cookie('api_password', $user->getApiPassword(), 0, '/', NULL, false, false);
            $data = $this->serialize($user, $_format);
            $response = $this->apiResponse($data, $_format);
            $response->headers->setCookie($cookie);
            $this->container->get('tavro.handler.users')->reauthenticate($user);
            return $response;
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

}