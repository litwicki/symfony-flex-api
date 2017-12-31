<?php namespace App\Controller\Entity;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Entity\Contact;
use App\Exception\Api\ApiException;
use App\Exception\Api\ApiNotFoundException;
use App\Exception\Api\ApiRequestLimitException;
use App\Exception\Api\ApiAccessDeniedException;
use App\Exception\Form\InvalidFormException;
use Symfony\Component\EventDispatcher\EventDispatcher;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\Tools\Pagination\Paginator;

use App\Entity\User;
use App\Entity\Account;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\Organization;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

use App\Controller\Api\EntityApiController;

class CommandController extends EntityApiController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Account $account
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
     * @param \App\Entity\Account $account
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