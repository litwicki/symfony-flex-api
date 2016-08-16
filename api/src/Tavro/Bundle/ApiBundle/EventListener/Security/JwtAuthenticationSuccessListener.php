<?php

namespace Tavro\Bundle\ApiBundle\EventListener\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Tavro\Bundle\CoreBundle\Model\UserInterface;

class JwtAuthenticationSuccessListener
{
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        // $data['token'] contains the JWT

        $data['data'] = array(
            'roles' => $user->getRoles(),
        );

        $event->setData($data);
    }
}
