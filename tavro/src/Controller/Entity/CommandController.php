<?php namespace Tavro\Controller\Entity;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Tavro\Entity\Contact;
use Tavro\Exception\Api\ApiException;
use Tavro\Exception\Api\ApiNotFoundException;
use Tavro\Exception\Api\ApiRequestLimitException;
use Tavro\Exception\Api\ApiAccessDeniedException;
use Tavro\Exception\Form\InvalidFormException;
use Symfony\Component\EventDispatcher\EventDispatcher;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Tavro\Entity\User;
use Tavro\Entity\Account;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;

use Tavro\Entity\Organization;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

use Tavro\Controller\Api\EntityApiController;

class CommandController extends EntityApiController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Entity\Account $account
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function hubspotImportAction(Request $request, Account $account, $_format)
    {
        $data = null;

        try {

            $kernel = $this->get('kernel');
            $application = new Application($kernel);
            $application->setAutoExit(false);

            $input = new ArrayInput(array(
               'command' => 'tavro:import:hubspot',
               '--account' => $account->getId(),
            ));

            $output = new BufferedOutput();
            $application->run($input, $output);

            $content = $output->fetch();
            $data = $content['data'];

            $options = [
                'format' => $_format,
                'message' => sprintf('%s Organizations and %s People imported, %s Contacts added.', $content['orgCount'], $content['personCount'], $content['contactCount'])
            ];

        }
        catch(\Exception $e) {
            $options = $this->getExceptionOptions($e, $_format);
        }

        return $this->apiResponse($data, $options);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Entity\Account $account
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function qboImportAction(Request $request, Account $account, $_format)
    {
        $data = null;

        try {

            $kernel = $this->get('kernel');
            $application = new Application($kernel);
            $application->setAutoExit(false);

            $input = new ArrayInput(array(
               'command' => 'tavro:import:qbo',
               '--account' => $account->getId(),
            ));

            $output = new BufferedOutput();
            $application->run($input, $output);

            $content = $output->fetch();
            $data = $content['data'];

            $options = [
                'format' => $_format,
                'message' => '',
            ];

        }
        catch(\Exception $e) {
            $options = $this->getExceptionOptions($e, $_format);
        }

        return $this->apiResponse($data, $options);
    }

}