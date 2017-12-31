<?php

namespace App\EventListener\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use App\Model\EntityInterface\UserInterface;

class JwtAuthenticatedListener
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
