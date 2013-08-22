<?php
namespace Ant\ChateaClient\Client;

use Ant\ChateaClient\OAuth2\AccessToken;
use Ant\ChateaClient\OAuth2\RefreshToken;
use Ant\ChateaClient\OAuth2\Scope;

abstract class ChateaAuth
{
	abstract public function authenticate();
	abstract public function updateToken();
	abstract public function revokeToken();
	
	abstract public function getAccessToken();
	abstract public function getRefreshToken();
	abstract public function isAccessTokenExpired();

}