<?php

namespace Ant\ChateaClient\Client;

use Ant\ChateaClient\OAuth2\AccessToken;
use Ant\ChateaClient\OAuth2\RefreshToken;
use Ant\ChateaClient\OAuth2\IOAuth2Client;

interface IStorage
{
   
    public function findAccessTokenByClientId(IOAuth2Client $client);
    public function addAccessToken(IOAuth2Client $client, AccessToken $accessToken);
    public function updateAccessToken(IOAuth2Client $client, AccessToken $accessToken);
    public function deleteAccessToken(IOAuth2Client $client);
    
    public function findRefreshTokenByClientId(IOAuth2Client $client);
    public function addRefreshToken(IOAuth2Client $client, RefreshToken $refreshToken);    
    public function updateRefreshToken(IOAuth2Client $client, RefreshToken $refreshToken);
    public function deleteRefreshToken(IOAuth2Client $client);
    
}
