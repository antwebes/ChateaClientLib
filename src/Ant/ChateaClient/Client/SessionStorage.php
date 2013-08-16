<?php

namespace Ant\ChateaClient\Client;

use Ant\ChateaClient\OAuth2\AccessToken;
use Ant\ChateaClient\OAuth2\RefreshToken;
class SessionStorage implements StorageInterface
{
    public function __construct()
    {
        if ("" === session_id()) {
            // no session currently exists, start a new one
            session_start();
        }
    }

    public function getAccessToken($client_id)
    {
        if (!isset($_SESSION['ant-chatea-client'][$client_id]['access_token'])) {
            return false;
        }

        $accessToken = unserialize($_SESSION['ant-chatea-client'][$client_id]['access_token']);

        return $accessToken ?$accessToken: false;

    }

    public function setAccessToken($client_id, AccessToken $accessToken)
    {
        if (!isset($_SESSION['ant-chatea-client'][$client_id]['access_token'])) {
            $_SESSION['ant-chatea-client'][$client_id]['access_token'] = array();
        }

        array_push($_SESSION['ant-chatea-client'][$client_id]['access_token'], serialize($accessToken));

        return true;
    }

    public function deleteAccessToken($client_id)
    {
        if (!isset($_SESSION['ant-chatea-client'][$client_id]['access_token'])) {
            return false;
        }

        unset($_SESSION['ant-chatea-client'][$client_id]['access_token']);
           
        return true;

    }

    public function getRefreshToken($client_id)
    {
        if (!isset($_SESSION['ant-chatea-client'][$client_id]['refresh_token'])) {
            return false;
        }

        $accessToken = unserialize($_SESSION['ant-chatea-client'][$client_id]['refresh_token']);

        return $accessToken ?$accessToken: false;
    }

    public function setRefreshToken($client_id, RefreshToken $refreshToken)
    {
        if (!isset($_SESSION['ant-chatea-client'][$client_id]['refresh_token'])) {
            $_SESSION['ant-chatea-client'][$client_id]['refresh_token'] = array();
        }

        array_push($_SESSION['ant-chatea-client'][$client_id]['refresh_token'], serialize($refreshToken));

        return true;
    }

    public function deleteRefreshToken($client_id)
    {
        if (!isset($_SESSION['ant-chatea-client'][$client_id]['access_token'])) {
            return false;
        }

        unset($_SESSION['ant-chatea-client'][$client_id]['access_token']);
           
        return true;
    }
}
