<?php
namespace Ant\ChateaClient\Client;

use Ant\ChateaClient\OAuth2\TokenRequestException;
use Ant\ChateaClient\OAuth2\OAuth2ClientCredentials;
use Ant\ChateaClient\Http\IHttpClient;
use Ant\ChateaClient\Client\OAuth2;
use Ant\ChateaClient\Client\AuthenticationException;


class AuthClientCredentials extends Authentication
{

	public function __construct(OAuth2ClientCredentials $oauthClient, IHttpClient $httpClient)
	{
		parent::__construct($oauthClient, $httpClient);
	}
	public function authenticate()
	{ 		
		$tokenRequest = $this->getTokenRequest();
		try{

			$tokenResponse =  $tokenRequest->withClientCredentials();								
			$this->setAccessToken($tokenResponse->getAccessToken());
			$this->setRefreshToken($tokenResponse->getRefreshToken());
					
			return $tokenResponse;			
				
		}catch (TokenRequestException $e){
			throw new AuthenticationException("Error fetching OAuth2 update token, message: " .
					$e->getMessage(), $this, $e->getCode(),$e);
		}	
	}
}