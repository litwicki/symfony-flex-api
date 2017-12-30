<?php

namespace Tavro\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Tavro\Controller\DefaultController;
use GuzzleHttp\Client;


class SlackController extends DefaultController
{

    /**
     * Send a Slack message.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $_format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function submitAction(Request $request, $_format)
    {
        $data = null;

        try {

            $url = $this->getParameter('slack_webhook_url');
            $channel = $this->getParamter('slack_channel');

            $data = $this->getPayload($request);

            $post = json_decode([
                'text' => $data['message'],
                'channel' => isset($data['channel']) ? $data['channel'] : $channel,
                'link_names' => 1,
                'username' => 'tavro-bot',
                'icon_emoji' => ':cheers:'
            ], TRUE);

            $client = new Client($url, array(
                'request.options' => array(
                    'exceptions' => false,
                )
            ));

            $request = $client->post($url, json_encode($post));
            $data = $request->send();

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