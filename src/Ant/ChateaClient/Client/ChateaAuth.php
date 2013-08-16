<?php
namespace Ant\ChateaClient\Client;
use Ant\ChateaClient\OAuth2\AccessToken;
use Ant\ChateaClient\OAuth2\RefreshToken;

abstract class ChateaAuth
{
	abstract public function authenticate();

	abstract public function createAuthUrl(Scope $scope);
	
	abstract public function getAccessToken();
	abstract public function setAccessToken(AccessToken $accessToken);
	
	abstract public function refreshToken(RefreshToken $refreshToken);
	abstract public function revokeToken();	
	
}