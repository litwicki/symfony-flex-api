<?php

namespace Tavro\Bundle\ApiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Litwicki\Common\cURL;
use Tavro\Bundle\CoreBundle\Entity\Variable;
use Tavro\Bundle\CoreBundle\Entity\Organization;
use Tavro\Bundle\CoreBundle\Entity\Contact;
use Tavro\Bundle\CoreBundle\Entity\Person;

class ImportHubspotCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tavro:import:hubspot')
            ->setDescription('Import and synchronize a Hubspot Account with a Tavro Account.')
            ->addOption('account', null, InputOption::VALUE_NONE, 'Account Id')
        ;
    }

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

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $import = array();
        
        $accountId = 0;

        /**
         * This is sloppy, but Symfony3 can't make up its mind if we need to use handleRequest
         * or simply submit() on a given FormType, so we have Request as a parameter for our
         * data handlers and pass it via controllers, but in Commands (for now anyway) we will
         * set to NULL and hope for the best..
         */
        $request = null;

        if ($input->getOption('option')) {
            $accountId = $input->getOption('account');
        }

        $var = $this->getDoctrine()->getRepository('TavroCoreBundle:Variable')->findOneBy([
            'name' => 'hubspot.hapikey',
            'account' => $accountId
        ]);

        if(!$var instanceof Variable) {
            throw new \Exception(sprintf('Error loading Hubspot Api Key for Account %s', $accountId));
        }

        $url = sprintf('https://api.hubapi.com/companies/v2/companies?hapikey=%s', $var->getValue());

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
                'account' => $accountId,
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

            $companyUrl = sprintf('https://api.hubapi.com/companies/v2/companies/%s/contacts?hapikey=%s', $companyId, $hapiKey);

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
                'account' => $accountId
            ]);

            if(!$organization instanceof Organization) {

                /**
                 * Still no Organization, let's look by Name..
                 */
                $organization = $this->getDoctrine()->getRepository('TavroCoreBundle:Organization')->findOneBy([
                    'name' => $item['name'],
                    'account' => $accountId
                ]);

                if(!$organization instanceof Organization) {
                    $data = $item;
                    unset($data['contacts']);
                    $handler = $this->getHandler('organizations');
                    $organization = $handler->post($request, $data);
                    $output->writeln(sprintf('Importing new Organization with name %s', $data['name']));
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

                            $output->writeln(sprintf('Importing new Person with email %s', $contact['email']));

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

                            $output->writeln(sprintf('Creating new Contact from Person with email %s', $contact['email']));

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

        return array(
            'data' => $import,
            'orgCount' => $orgCount,
            'personCount' => $personCount,
            'contactCount' => $contactCount
        );

    }

}
