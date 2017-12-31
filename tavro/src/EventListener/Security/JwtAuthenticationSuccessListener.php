<?php

namespace App\EventListener\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use App\Model\EntityInterface\UserInterface;

class JwtAuthenticationSuccessListener
{
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        /**
         * Do NOT use getRoles() as that is a serialized
         * response for standard simple_form authentication.
         */
        $data['roles'] = $user->getRoleNames();

        $event->setData($data);
    }
}
