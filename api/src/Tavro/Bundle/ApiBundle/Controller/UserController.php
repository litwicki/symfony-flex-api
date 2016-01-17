<?php

namespace Tavro\Bundle\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Tavro\Bundle\ApiBundle\Exception\ApiException;
use Tavro\Bundle\ApiBundle\Exception\ApiNotFoundException;
use Tavro\Bundle\ApiBundle\Exception\ApiRequestLimitException;
use Tavro\Bundle\ApiBundle\Exception\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Tavro\Bundle\CoreBundle\Entity\User;
use Symfony\Component\HttpFoundation\Cookie;

use Litwicki\Common\Common;

class UserController extends ApiController
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
            $handler = $this->container->get('tavro.handler.users');
            $handler->resetApiKey($user);
            $cookie = new Cookie('api_key', $user->getApiKey(), 0, '/', NULL, false, false);
            $data = $this->serialize($user, $_format);
            $response = $this->apiResponse($data, $_format);
            $response->headers->setCookie($cookie);
            $handler->reauthenticate($user);
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
            $handler = $this->container->get('tavro.handler.users');
            $handler->resetApiPassword($user);
            $cookie = new Cookie('api_password', $user->getApiPassword(), 0, '/', NULL, false, false);
            $data = $this->serialize($user, $_format);
            $response = $this->apiResponse($data, $_format);
            $response->headers->setCookie($cookie);
            $handler->reauthenticate($user);
            return $response;
        }
        catch(\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    public function createUserOrganization(Request $request, User $user)
    {

    }

}