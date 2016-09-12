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
use Symfony\Component\EventDispatcher\EventDispatcher;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Tavro\Bundle\CoreBundle\Entity\User;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;

use Tavro\Bundle\CoreBundle\Common\Curl;
use Tavro\Bundle\CoreBundle\Entity\Organization;
use Tavro\Bundle\ApiBundle\Controller\DefaultController as ApiController;

class HubspotController extends ApiController
{

    protected function getUri()
    {
        $hapikey = '9df40eef-9d0f-40cd-9634-78df871d8803';

        return 'https://api.hubapi.com/integrations/v1/tavro/timeline/event-types?hapikey={HAPIKEY}&userId={USERID}';
    }

    public function importAction(Request $request, Organization $organization, $_format)
    {
        try {

            $var = $this->getDoctrine()->getRepository('TavroCoreBundle:Variable')->findOneBy([
                'name' => 'hubspot.hapikey',
                'organization' => $organization->getId()
            ]);

            $url = sprintf('https://api.hubapi.com/companies/v2/companies?hapikey=%s', $var->getBody());

            $curl = new cURL();
            $curl->get($url);
            $response = $curl->response;

            $data = json_decode($response, true);
            $people = array();

            foreach($data['companies'] as $contact) {

                if(isset($contact['properties']['firstname']['value'])) {
                    $person = [
                        'firstname' => $contact['properties']['firstname']['value'],
                        'lastname' => $contact['properties']['lastname']['value']
                    ];
                }

                if(isset($contact['identity-profiles'])) {

                    foreach ($contact['identity-profiles'] as $profile) {
                        if(isset($profile['identities'])) {
                            foreach($profile['identities'] as $identity) {
                                if($identity['type'] == 'EMAIL') {
                                    $person['email'] = $identity['value'];
                                }
                            }
                        }
                    }

                }

                $people[] = $person;

            }



            $data = $this->serialize($people, $_format);
            return $this->apiResponse($data, $_format);

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

}