<?php

namespace Tavro\Bundle\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiNotFoundException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiRequestLimitException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Tavro\Bundle\CoreBundle\Entity\User;
use Symfony\Component\HttpFoundation\Cookie;

use Litwicki\Common\Common;
use Tavro\Bundle\ApiBundle\Controller\DefaultController as ApiController;

class AuthController extends ApiController
{

    /**
     * By design, only allow the current user to do this for his/her self.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $format
     *
     * @throws \Exception
     */
    public function resetAction(Request $request, $format)
    {
        try {
            $handler = $this->container->get('tavro.handler.users');
            $user = $this->getUser();
            $data = json_decode($request->getContent(), true);
            $handler->resetPassword($user, $data);
        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * When a User has forgotten their password, set a reset token and email forcing
     * them to verify themselves before allowing the actual reset to complete.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $format
     *
     * @throws \Exception
     */
    public function forgotAction(Request $request, $format)
    {
        try {
            $handler = $this->container->get('tavro.handler.users');
            $user = $this->getUser();
            $handler->forgotPassword($user);
        }
        catch(ApiAccessDeniedException $e) {
            throw $e;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

}