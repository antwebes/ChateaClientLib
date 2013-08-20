<?php
namespace Ant\ChateaClient\Client;

use Ant\ChateaClient\OAuth2\AccessToken;
use Ant\ChateaClient\OAuth2\RefreshToken;
use Ant\ChateaClient\OAuth2\Scope;

abstract class ChateaAuth
{
	abstract public function authenticate($username, $password);

	
	abstract public function getAccessToken();
	abstract public function setAccessToken(AccessToken $accessToken);
	
	abstract public function refreshToken(RefreshToken $refreshToken);
	abstract public function revokeToken();	
	
}