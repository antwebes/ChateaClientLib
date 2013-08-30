<?php
namespace Ant\ChateaClient\Client;

use Ant\ChateaClient\OAuth2\TokenRequestException;
use Ant\ChateaClient\OAuth2\OAuth2ClientUserCredentials;
use Ant\ChateaClient\Http\IHttpClient;
use Ant\ChateaClient\Client\OAuth2;
use Ant\ChateaClient\Client\AuthenticationException;


class AuthUserCredentials extends Authentication
{

	public function __construct(OAuth2ClientUserCredentials $oauthClient, IHttpClient $httpClient)
	{
		parent::__construct($oauthClient, $httpClient);
	}
	public function getUsername()
	{
		return $this->getOAuthClient()->getUsername();
	}
	
	public function getPassword()
	{
		return $this->getOAuthClient()->getPassword();
	}
	public function authenticate()
	{ 
		$tokenRequest = $this->getTokenRequest();
		
		try{
						
			$tokenResponse =  $tokenRequest->withUserCredentials($this->getUsername(), $this->getPassword());					
			$this->setAccessToken($tokenResponse->getAccessToken());
			$this->setRefreshToken($tokenResponse->getRefreshToken());
					
			return $this;			
				
		}catch (TokenRequestException $e){
			throw new AuthenticationException("Error fetching OAuth2 update token, message: " .
					$e->getMessage(),$this);
		}	
		return true;		 
	}
}