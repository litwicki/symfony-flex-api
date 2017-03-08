<?php

namespace Tavro\Bundle\ApiBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Tavro\Bundle\ApiBundle\Controller\Api\ApiController as ApiController;

class LogController extends ApiController
{
    /**
     * @param Request $request
     * @param $_format
     * @return Response
     * @throws \Exception
     */
    public function create(Request $request, $_format)
    {
        $data = null;

        try {

            $data = json_decode($request->getContent(), TRUE);
            $logger = $this->get('logger');

            if(method_exists($logger, $data['type'])) {

                $type = $data['type'];
                $logger->$type($data['message']);

                if($type == 'emergency') {
                    $now = new \DateTime();
                    $now->setTimezone(new \DateTimeZone($this->getParameter('timezone')));

                    $this->get('tavro_mailer')->send([
                        'subject' => sprintf('%s - Emergency Log Entry', $now->format('Y-m-d h:i:s')),
                        'recipients' => $this->getParameter('app_email'),
                        'message' => $data['message']
                    ]);
                }

            }
            else {
                $logger->notice($data['message']);
            }

            $data['response'] = 'Log entry was created successfully';

            $options = [
                'format' => $_format
            ];

        }
        catch(\Exception $e) {
            $options = $this->getExceptionOptions($e, $_format);
        }

        return $this->apiResponse($data, $options);
    }
}