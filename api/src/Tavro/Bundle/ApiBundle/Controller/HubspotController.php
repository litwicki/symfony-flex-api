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
            $contactCount = $orgCount = $personCount = 0;

            foreach($data['companies'] as $row) {

                $company = $row['properties'];
                $companyId = $row['companyId'];

                $item = [
                    'account' => $account->getId(),
                    'address' => isset($company['address']['value']) ? $company['address']['value'] : null,
                    'address2' => isset($company['address2']['value']) ? $company['address2']['value'] : null,
                    'city' => isset($company['city']['value']) ? $company['city']['value'] : null,
                    'state' => isset($company['state']['value']) ? $company['state']['value'] : null,
                    'zip' => isset($company['zip']['value']) ? $company['zip']['value'] : null,
                    'website' => isset($company['website']['value']) ? $company['website']['value'] : null,
                    'phone' => isset($company['phone']['value']) ? $company['phone']['value'] : null,
                    'name' => isset($company['name']['value']) ? $company['name']['value'] : null,
                    'hubspot_id' => isset($row['companyId']) ? $row['companyId'] : null,
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

            /**
             * From the built array of companies and contacts, let's compare
             * the existing database and import what we don't have yet..
             */
            foreach($items as $item) {

                /**
                 * First look by hubspot_id in case we changed the Organization name after the last import..
                 */
                $organization = $this->getDoctrine()->getRepository('TavroCoreBundle:Organization')->findOneBy([
                    'hubspot_id' => $item['hubspot_id'],
                    'account' => $account->getId()
                ]);

                if(!$organization instanceof Organization) {

                    /**
                     * Still no Organization, let's look by Name..
                     */
                    $organization = $this->getDoctrine()->getRepository('TavroCoreBundle:Organization')->findOneBy([
                        'name' => $item['name'],
                        'account' => $account->getId()
                    ]);

                    if(!$organization instanceof Organization) {
                        $data = $item;
                        unset($data['contacts']);
                        $handler = $this->getHandler('organizations');
                        $organization = $handler->post($request, $data);
                        $orgCount++;
                    }

                }

                foreach($item['contacts'] as $contact) {

                    if(isset($contact['email'])) {

                        $person = $this->getDoctrine()->getRepository('TavroCoreBundle:Person')->findOneBy([
                            'email' => $contact['email']
                        ]);

                        if(!$person) {

                            $handler = $this->getHandler('people');

                            try {

                                $person = $handler->post($request, [
                                    'first_name' => isset($contact['first_name']) ? $contact['first_name'] : 'FIRST_NAME',
                                    'last_name' => isset($contact['last_name']) ? $contact['last_name'] : 'LAST_NAME',
                                    'email' => $contact['email']
                                ]);

                                $personCount++;

                            }
                            catch(\Exception $e) {
                                throw $e;
                            }

                        }

                        $contact = $this->getDoctrine()->getRepository('TavroCoreBundle:Contact')->findOneBy([
                            'person' => $person->getId()
                        ]);

                        if(!$contact instanceof Contact) {

                            $handler = $this->getHandler('contacts');

                            try {

                                $contact = $handler->post($request, [
                                    'email' => $contact['email'],
                                    'person' => $person->getId(),
                                    'organization' => $organization->getId(),
                                    'account' => $account->getId()
                                ]);

                                $contactCount++;

                            }
                            catch(\Exception $e) {
                                throw $e;
                            }

                        }

                        $import[] = $contact;

                    }

                }

            }

            return $this->apiResponse($import, [
                'format' => $_format,
                'message' => sprintf('%s Organizations and %s People imported, %s Contacts added.', $orgCount, $personCount, $contactCount)
            ]);

        }
        catch(\Exception $e) {
            throw $e;
        }
    }

}