<?php

namespace Tavro\Bundle\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiNotFoundException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiRequestLimitException;
use Tavro\Bundle\CoreBundle\Exception\Api\ApiAccessDeniedException;
use Tavro\Bundle\CoreBundle\Exception\Form\InvalidFormException;

use Doctrine\Common\Inflector\Inflector;

use Litwicki\Common\Common;

class DefaultController extends Controller
{
    public function create(Request $request, $_format)
    {
        try {

            $data = json_decode($request->getContent(), TRUE);
            $logger = $this->get('logger');

            switch($data['type']) {

                case 'notice':
                    $logger->notice($data['message']);
                    break;

                case 'debug':
                    $logger->debug($data['message']);
                    break;

                case 'info':
                    $logger->info($data['message']);
                    break;

                case 'warning':
                    $logger->warning($data['message']);
                    break;

                case 'error':
                    $logger->error($data['message']);
                    break;

                case 'critical':
                    $logger->critical($data['message'], array(
                        'cause' => $data['cause']
                    ));
                    break;

                case 'emergency':

                    $logger->emergency($data['message'], array(
                        'cause' => $data['cause']
                    ));

                    $now = new \DateTime();
                    $now->setTimezone(new \DateTimeZone($this->container->getParameter('timezone')));

                    $this->container->get('tavro_mailer')->send([
                       'subject' => sprintf('%s - Emergency Log Entry', $now->format('Y-m-d h:i:s'))
                    ]);

                    break;

                default:
                    break;

            }

            $data['response'] = 'Log entry was created successfully';

            return $this->apiResponse($data, [
                'format' => $_format
            ]);

        }
        catch(\Exception $e) {
            throw $e;
        }
    }
}