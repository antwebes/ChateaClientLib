<?php

namespace Ant\ChateaClient\Client;

use Ant\ChateaClient\OAuth2\AccessToken;
use Ant\ChateaClient\OAuth2\RefreshToken;

interface StorageInterface
{
   
    public function getAccessToken($client_id);
    public function setAccessToken($client_id, AccessToken $accessToken);
    public function deleteAccessToken($client_id);

    public function getRefreshToken($client_id);
    public function setRefreshToken($client_id, RefreshToken $refreshToken);    
    public function deleteRefreshToken($client_id);
    
}
