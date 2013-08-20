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
        if (!isset($_SESSION['ant-chatea-client']['access_token'][$client_id])) {
            return false;
        }
        $accessToken = null;
        
        $accessToken = unserialize($_SESSION['ant-chatea-client']['access_token'][$client_id]);
        
        
        return $accessToken ? $accessToken: false;

    }

    public function setAccessToken($client_id, AccessToken $accessToken)
    {
        if (!isset($_SESSION['ant-chatea-client']['access_token'])) {
            $_SESSION['ant-chatea-client']['access_token'] = array();
        }

        $_SESSION['ant-chatea-client']['access_token'][$client_id] = serialize($accessToken);

        return true;
    }

    public function deleteAccessToken($client_id)
    {
        if (!isset($_SESSION['ant-chatea-client']['access_token'][$client_id])) {
            return false;
        }

        unset($_SESSION['ant-chatea-client']['access_token'][$client_id	]);
           
        return true;

    }

    public function getRefreshToken($client_id)
    {
        if (!isset($_SESSION['ant-chatea-client']['refresh_token'][$client_id])) {
            return false;
        }

        $accessToken = unserialize($_SESSION['ant-chatea-client'][$client_id]['refresh_token']);

        return $accessToken ?$accessToken: false;
    }

    public function setRefreshToken($client_id, RefreshToken $refreshToken)
    {
        if (!isset($_SESSION['ant-chatea-client']['refresh_token'][$client_id])) {
            $_SESSION['ant-chatea-client']['refresh_token'][$client_id] = array();
        }

        array_push($_SESSION['ant-chatea-client']['refresh_token'][$client_id], serialize($refreshToken));

        return true;
    }

    public function deleteRefreshToken($client_id)
    {
        if (!isset($_SESSION['ant-chatea-client']['access_token'][$client_id][$client_id])) {
            return false;
        }

        unset($_SESSION['ant-chatea-client']['access_token'][$client_id]);
           
        return true;
    }
}
