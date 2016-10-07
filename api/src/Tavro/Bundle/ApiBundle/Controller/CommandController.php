<?php

namespace Tavro\Bundle\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Tavro\Bundle\CoreBundle\Entity\Contact;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiNotFoundException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiRequestLimitException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;
use Symfony\Component\EventDispatcher\EventDispatcher;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Entity\Account;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;

use Tavro\Bundle\CoreBundle\Common\Curl;
use Tavro\Bundle\CoreBundle\Entity\Organization;
use Tavro\Bundle\ApiBundle\Controller\DefaultController as ApiController;

class HubspotController extends ApiController
{

    /**
     * @return string
     */
    protected function getQboKey()
    {
        return '9df40eef-9d0f-40cd-9634-78df871d8803';
    }

    /**
     * @return string
     */
    protected function getUri()
    {
        $hapiKey = $this->getQboKey();

        return 'https://api.hubapi.com/integrations/v1/tavro/timeline/event-types?hapikey={HAPIKEY}&userId={USERID}';
    }

}