<?php

namespace Ant\ChateaClient\Client;

use Ant\ChateaClient\OAuth2\AccessToken;
use Ant\ChateaClient\OAuth2\RefreshToken;

interface StorageInterface
{
   
    public function findAccessTokenByClientId($client_id);
    public function addAccessToken($client_id, AccessToken $accessToken);
    public function updateAccessToken($client_id, AccessToken $accessToken);
    public function deleteAccessToken($client_id);
    
    public function findRefreshTokenByClientId($client_id);
    public function addRefreshToken($client_id, RefreshToken $refreshToken);    
    public function updateRefreshToken($client_id, RefreshToken $refreshToken);
    public function deleteRefreshToken($client_id);
    
}
