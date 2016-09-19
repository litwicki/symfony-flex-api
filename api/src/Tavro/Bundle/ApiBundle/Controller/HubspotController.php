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
    protected function getHapiKey()
    {
        return '9df40eef-9d0f-40cd-9634-78df871d8803';
    }

    /**
     * @return string
     */
    protected function getUri()
    {
        $hapiKey = $this->getHapiKey();
        return 'https://api.hubapi.com/integrations/v1/tavro/timeline/event-types?hapikey={HAPIKEY}&userId={USERID}';
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function importAction(Request $request, Account $account, $_format)
    {
        try {

            $var = $this->getDoctrine()->getRepository('TavroCoreBundle:Variable')->findOneBy([
                'name' => 'hubspot.hapikey',
                'account' => $account->getId()
            ]);

            $url = sprintf('https://api.hubapi.com/companies/v2/companies?hapikey=%s', $this->getHapiKey());

            $curl = new cURL();
            $curl->get($url);
            $response = $curl->response;

            $data = json_decode($response, true);

            $items = array();

            foreach($data['companies'] as $row) {

                $company = $row['properties'];
                $companyId = $row['companyId'];

                $item = [
                    'address' => isset($company['address']['value']) ? $company['address']['value'] : null,
                    'address2' => isset($company['address2']['value']) ? $company['address2']['value'] : null,
                    'city' => isset($company['city']['value']) ? $company['city']['value'] : null,
                    'state' => isset($company['state']['value']) ? $company['state']['value'] : null,
                    'zip' => isset($company['zip']['value']) ? $company['zip']['value'] : null,
                    'website' => isset($company['website']['value']) ? $company['website']['value'] : null,
                    'phone' => isset($company['phone']['value']) ? $company['phone']['value'] : null,
                    'name' => isset($company['name']['value']) ? $company['name']['value'] : null,
                    'hubspot_id' => isset($row['portalId']) ? $row['portalId'] : null,
                    'description' => isset($company['description']['value']) ? $company['description']['value'] : null,
                ];

                $companyUrl = sprintf('https://api.hubapi.com/companies/v2/companies/%s/contacts?hapikey=%s', $companyId, $this->getHapiKey());

                $curl = new cURL();
                $curl->get($companyUrl);
                $response = $curl->response;

                $contacts = json_decode($response, true);

                $people = array();

                foreach($contacts as $contact) {

                    if(!is_array($contact)) {
                        break;
                    }

                    $contact = reset($contact);

                    $properties = isset($contact['properties']) ? $contact['properties'] : array();
                    $identities = isset($contact['identities']) ? $contact['identities'] : array();
                    $person = array();

                    foreach($properties as $property) {

                        if(isset($property['name']) && $property['name'] == 'firstname') {
                            $person['first_name'] = $property['value'];
                        }

                        if(isset($property['name']) && $property['name'] == 'lastname') {
                            $person['last_name'] = $property['value'];
                        }

                    }

                    foreach($identities as $identity) {

                        foreach($identity['identity'] as $data) {
                            if(isset($data['type']) && $data['type'] == 'EMAIL') {
                                $person['email'] = $data['value'];
                            }
                        }

                    }

                    $item['contacts'][] = $person;

                    $items[] = $item;

                }

            }

            foreach($items as $item) {

                $organization = $this->getDoctrine()->getRepository('TavroCoreBundle:Organization')->findOneBy([
                    'name' => $item['name']
                ]);

                if(!$organization) {

                    $data = $item;
                    unset($data['contacts']);

                    $handler = $this->getHandler('organizations');
                    $organization = $handler->post($request, $data);

                }

                foreach($item['contacts'] as $contact) {

                    $person = $this->getDoctrine()->getRepository('TavroCoreBundle:Person')->findOneBy([
                        'email' => $item['email']
                    ]);

                    if(!$person) {

                        $handler = $this->getHandler('people');
                        $person = $handler->post($request, [
                            'first_name' => $contact['firstname'],
                            'last_name' => $contact['lastname'],
                            'email' => $contact['email']
                        ]);

                    }

                    $contact = $this->getDoctrine()->getRepository('TavroCoreBundle:Contact')->findOneBy([
                        'person' => $person->getId()
                    ]);

                    if(!$contact) {

                        $handler = $this->getHandler('contact');
                        $contact = $handler->post($request, [
                            'email' => $item['email'],
                            'person' => $person->getId(),
                            'organization' => $organization->getId()
                        ]);

                    }

                }

            }

            $data = $this->serialize($items, $_format);
            return $this->apiResponse($data, $_format);

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

}