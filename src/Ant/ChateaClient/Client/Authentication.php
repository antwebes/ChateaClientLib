<?php
namespace Ant\ChateaClient\Client;

use Ant\ChateaClient\OAuth2\TokenRequest;
use Ant\ChateaClient\OAuth2\TokenRequestException;
use Ant\ChateaClient\OAuth2\AccessToken;
use Ant\ChateaClient\OAuth2\RefreshToken;
use Ant\ChateaClient\OAuth2\TokenResponse;
use Ant\ChateaClient\OAuth2\Scope;
use Ant\ChateaClient\OAuth2\IOAuth2Client;
use Ant\ChateaClient\Http\IHttpClient;
use Ant\ChateaClient\Http\HttpClientException;
use Ant\ChateaClient\OAuth2\TokenException;

abstract class Authentication implements IAuthentication 
{
		
	
	private $oauthClient;
	private $httpClient;
	private $accesToken;
	private $refreshToken;
	    
	protected function __construct(IOAuth2Client $oauthClient, IHttpClient $httpClient)
	{
		if(!$oauthClient){
			throw ConfigException("OAuth2Client missing field, it is not null");
		}
		
		if(!$httpClient){
			throw ConfigException("OAuth2Client missing field, it is not null");
		}

		$this->oauthClient = $oauthClient;
		$this->httpClient = $httpClient;
        $this->httpClient->setBaseUrl(IHttpClient::TOKEN_ENDPOINT);
		$this->accesToken = null;
		$this->refreshToken = null;
	}
	
	public function getClientId()
	{
		return $this->oauthClient->getPublicId();
	}
	/**
	 * @return \Ant\ChateaClient\OAuth2\AccessToken
	 */
	public function getAccessToken($asString = false) 
	{
		return $asString && $this->accesToken?$this->accesToken->getValue():$this->accesToken;
	}
	
	protected function setAccessToken(AccessToken $accesToken)
	{
		if(null === $accesToken){
			throw new TokenException('AccesToken is not null');
		}
		
		$this->accesToken = $accesToken;		
	}	
	/**
	 * 
	 * @return \Ant\ChateaClient\OAuth2\RefreshToken
	 */
	public function getRefreshToken($asString = false)
	{
		return $asString && $this->refreshToken ? $this->refreshToken->getValue(): $this->refreshToken;
	}
	protected function setRefreshToken(RefreshToken $refreshToken)
	{
		$this->refreshToken = $refreshToken;
	}	

	/**
	 * Returns true if the access_token is expired.
	 *
	 * @return bool Returns True if the access_token is expired.
	 */
	public function isAuthenticationExpired()
	{
		return $this->getAccessToken()?$this->getAccessToken()->hasExpired():true;
	}
	/**
	 * 
	 * @return \Ant\ChateaClient\OAuth2\TokenRequest
	 */
	protected function getTokenRequest()
	{
		return new TokenRequest($this->oauthClient,$this->httpClient);
	}
	/**
	 * 
	 * @return IOAuth2Client
	 */
	protected function getOAuthClient(){
		return $this->oauthClient;
	}
	protected function getHttpClient(){
		return $this->httpClient;
	}
		
	public abstract  function authenticate();

	
	public function updateToken(RefreshToken $refreshToken = null) 
	{

		$tokenRequest = $this->getTokenRequest();
		
		try{
			if($refreshToken != null){
				$this->refreshToken = $refreshToken;
			}	
					
			$tokenResponse =  $tokenRequest->withRefreshToken($this->refreshToken);		
			
			$this->accesToken 	= $tokenResponse->getAccessToken();			
			$this->refreshToken = $tokenResponse->getRefreshToken();
					
			return $this;			
				
		}catch (TokenRequestException $e){
			throw new AuthenticationException("Error fetching OAuth2 update token, message: " .
					$e->getMessage());
		}	
		return true;		
	}
	public static function updateWithRefreshToken(IHttpClient $httpClient, $client_id, $secret, $refreshToken)
	{
	    if($httpClient === null){
	        throw new AuthenticationException("The httpClient is not null");
	    }
	    if (!is_string($client_id) || 0 >= strlen($client_id) ){
	        throw new AuthenticationException("The client_id needs to be a non-empty string");
	    }
	    if (!is_string($secret) || 0 >= strlen($secret) ){
	        throw new AuthenticationException("The secret needs to be a non-empty string");
	    }
	    if($refreshToken === null){
	        throw new AuthenticationException("The refreshToken is not null");
	    }
	    	    
	    $httpClient->setBaseUrl(IHttpClient::TOKEN_ENDPOINT);

	   
	    try{
	    
	        $tokenResponse =  TokenRequest::updateWithRefreshToken($httpClient, $client_id, $secret, $refreshToken);	        	
	        return $tokenResponse;
	    
	    }catch (TokenRequestException $e){
	        throw new AuthenticationException("Error fetching OAuth2 update token, message: " .
	            $e->getMessage(), null, $e->getCode(),$e);
	    }
	    	    
	}
	public function revokeToken() 
	{
	
		$accesToken = $this->getAccessToken();
		
		if(!$accesToken){
			$tokenRequest = $this->getTokenRequest();
			try{

				$this->httpClient->setBaseUrl(IHttpClient::REVOKE_ENDPOINT);
				$response = $this->httpClient->send();				
				$this->accesToken = new AccessToken('not-acces-token');
				$this->refreshToken = new RefreshToken('not-refrest-token');
				
				return $this;
				
				}catch (HttpClientException $e){
				throw new AuthenticationException("Error fetching OAuth2 revoke token, message: " . 
						$e->getMessage());
			}		
		}
	}	
}