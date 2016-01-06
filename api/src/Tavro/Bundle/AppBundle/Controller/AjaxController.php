<?php

namespace Tavro\Bundle\AppBundle\Controller;

use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Tavro\Bundle\CoreBundle\Entity\User;
use Litwicki\Common\Common;

class AjaxController extends Controller
{
    /**
     * @param $data
     * @param string $format
     * @param int $code
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function ajaxResponse($data, $format = 'json', $code = 200)
    {
        try {

            $response = new Response($data);

            if($format == 'json') {
                $response->headers->set('Content-Type', 'application/json');
            }
            else {
                $response->headers->set('Content-Type', 'application/xml');
            }

            $response->setStatusCode($code);

        }
        catch(\Exception $e) {
            throw $e;
        }

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function rolesAction(Request $request, User $user, $_format)
    {
        try {

            $data = $this->container->get('tavro.handler.users')->getUserRoles($user);
            $serializer = $this->container->get('tavro_serializer');

            if($_format == 'json') {
                $string = $serializer->serialize($data, 'json', 'summary');
            }
            else {
                $string = $serializer->serialize($data, 'xml', 'summary');
            }

            return $this->ajaxResponse($string, $_format);
        }
        catch(\Exception $e) {
            throw $e;
        }
    }
}