<?php
namespace Ant\ChateaClient\Client;

use Ant\ChateaClient\OAuth2\TokenRequestException;
use Ant\ChateaClient\OAuth2\OAuth2ClientAuthCode;
use Ant\ChateaClient\Http\IHttpClient;
use Ant\ChateaClient\Client\OAuth2;
use Ant\ChateaClient\Client\AuthenticationException;

class AuthAuthCode extends Authentication
{

	public function __construct(OAuth2ClientAuthCode $oauthClient, IHttpClient $httpClient)
	{
		parent::__construct($oauthClient, $httpClient);
	}
	public function getAuthCode()
	{
		return $this->getOAuthClient()->getAuthCode();
	}
	public function authenticate()
	{ 
		$tokenRequest = $this->getTokenRequest();
		
		try{
						
			$tokenResponse =  $tokenRequest->withAuthorizationCode($this->getAuthCode());					
			$this->setAccessToken($tokenResponse->getAccessToken());
			$this->setRefreshToken($tokenResponse->getRefreshToken());
					
			return $tokenResponse;			
				
		}catch (TokenRequestException $e){
			throw new AuthenticationException("Error fetching OAuth2 update token, message: " .
					$e->getMessage(),$this);
		}	
		return true;		 
	}
}