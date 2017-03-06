<?php namespace Tavro\Bundle\ApiBundle\Controller\Entity;

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

use Litwicki\Common\cURL;
use Tavro\Bundle\CoreBundle\Entity\Organization;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

use Tavro\Bundle\ApiBundle\Controller\Api\ApiController as ApiController;

class CommandController extends ApiController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
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
            $options = [
                'format' => $_format,
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }

        return $this->apiResponse($data, $options);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
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
            $options = [
                'format' => $_format,
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }

        return $this->apiResponse($data, $options);
    }

}