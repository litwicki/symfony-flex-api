<?php

namespace Litwicki\Quickbooks\Online;

class ApiRequest
{

    public function __construct(\Oauth $oauth, array $configs)
    {
        $oauth = new \OAuth($configs['ConsumerKey'], $configs['ConsumerSecret']);
        $oauth->setToken($configs['AccessToken'], $configs['AccessTokenSecret']);
        $oauth->enableDebug();
        $oauth->setAuthType(OAUTH_AUTH_TYPE_AUTHORIZATION);
        $oauth->disableSSLChecks();
    }

    public function request()
    {
        $headers = array('accept' => 'application/json');
        $response = $oauth->fetch($uri, null, OAUTH_HTTP_METHOD_GET, $headers);
    }

}