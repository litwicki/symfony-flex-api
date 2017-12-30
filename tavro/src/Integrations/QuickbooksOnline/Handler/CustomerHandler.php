<?php namespace Tavro\QuickbooksOnline\Handler;

use Doctrine\ORM\EntityManager;
use \OAuth;

use Tavro\Entity\Person;
use Tavro\Entity\Organization;
use Tavro\Entity\Contact;

use Tavro\QuickbooksOnline\QboApiService;

/**
 * Class CustomerHandler
 *
 * This package handles the processing of Customers, which we import
 * as Person, Customer, and Organization Entities where applicable.
 *
 * @package Tavro\QuickbooksOnline\Handler
 */
class CustomerHandler extends QboApiService
{

    public function import($id, $debug = true)
    {

        $account = $this->em->getRepository('TavroCoreBundle:Account')->find($id);
        $oauth = $this->connect($account);
        $productCount = $serviceCount = $revenueCount = 0;

        /**
         * @TODO: Should we filter out records that already have a qbo_id??
         */
        $query = 'SELECT * FROM Customer';

        $url = sprintf('%s/company/%s/query?query=%s',
            $this->getBaseUrl($debug),
            $this->realmId,
            $this->buildQuery($query)
        );

        $oauth->fetch($url);
        $response = $oauth->getLastResponse();

        $customers = $response['QueryResponse']['Customer'];

        $entities = $organizations = $contacts = array();

        foreach ($customers as $customer) {

            $id = $customer['Id'];

            $person = $this->em->getRepository('TavroCoreBundle:Person')->findOneBy([
                'account' => $account->getId(),
                'qbo_id' => $id
            ]);

            if(!$person instanceof Person) {

                $fullyQualifiedName = $customer['FullyQualifiedName'];
                $name = explode(' ', $fullyQualifiedName);

                $email = $phone = '';

                if(isset($customer['PrimaryEmailAddr']['Address'])) {
                    $email = $customer['PrimaryEmailAddr']['Address'];
                }

                if(isset($customer['PrimaryPhone']['FreeFormNumber'])) {
                    $phone = $customer['PrimaryPhone']['FreeFormNumber'];
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {

                    /**
                     * No Person, so create one!
                     *
                     * @TODO: should we add the qbo_id of the "Customer" here
                     * or on the Contact Entity?
                     *
                     */
                    $person = $this->container->get('tavro.handler.people')->create([
                        'first_name' => $name[0],
                        'middle_name' => isset($name[2]) ? $name[1] : $name[1],
                        'last_name' => isset($name[2]) ? $name[2] : $name[1],
                        'phone' => $phone,
                        'email' => $email,
                        'address' => $customer['BillAddr']['Line1'],
                        'city' => $customer['BillAddr']['City'],
                        'state' => $customer['BillAddr']['CountrySubDivisionCode'],
                        'zip' => $customer['BillAddr']['PostalCode']
                    ]);

                }

            }

            /**
             * This is a Person, but does it belong to the "CompanyName" here?
             */
            $org = $this->em->getRepository('TavroCoreBundle:Organization')->findOneBy([
                'account' => $account->getId(),
                'name' => $customer['CompanyName']
            ]);

            if(!$org instanceof Organization) {

                /**
                 * This person needs to be added to the Org
                 */
                $organizations[] = $this->container->get('tavro.handler.organizations')->create([
                    'account' => $account->getId(),
                    'person' => $person->getId(),
                    'name' => $customer['CompanyName']
                ]);

            }

            $contact = $this->em->getRepository('TavroCoreBundle:Contact')->findOneBy([
                'account' => $account->getId(),
                'person' => $person->getId(),
                'organization' => $org->getId()
            ]);

            if(!$contact instanceof Contact) {

                /**
                 * This person needs to be added to the Org
                 */
                $contacts[] = $this->container->get('tavro.handler.contacts')->create([
                    'account' => $account->getId(),
                    'person' => $person->getId(),
                ]);

            }

        }

        $message = sprintf('%s Customers were processed with %s Organizations', count($contacts), count($organizations));

        return [
            'data' => $entities,
            'message' => $message
        ];

    }

}