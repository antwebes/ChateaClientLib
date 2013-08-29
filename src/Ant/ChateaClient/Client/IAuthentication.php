<?php
namespace Ant\ChateaClient\Client;

use Ant\ChateaClient\OAuth2\AccessToken;
use Ant\ChateaClient\OAuth2\RefreshToken;
use Ant\ChateaClient\OAuth2\Scope;

interface IAuthentication
{
	public function authenticate();
	public function updateToken();
	public function revokeToken();
	
	public function getAccessToken();
	public function getRefreshToken();
	public function isAuthenticationExpired();

}