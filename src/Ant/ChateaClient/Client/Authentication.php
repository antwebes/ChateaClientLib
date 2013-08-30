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
	private $expires_at;
	    
	protected function __construct(IOAuth2Client $oauthClient, IHttpClient $httpClient)
	{
		if(!$oauthClient){
			throw ConfigException("OAuth2Client missing field, it is not null");
		}
		
		if(!$httpClient){
			throw ConfigException("OAuth2Client missing field, it is not null");
		}
		if (!is_string($httpClient->getUrl()) || 0 >= strlen($httpClient->getUrl())) {
			$this->httpClient->setBaseUrl(IHttpClient::TOKEN_ENDPOINT);
		}		
		$this->oauthClient = $oauthClient;
		$this->httpClient = $httpClient;
		$this->accesToken = new AccessToken('not-acces-token');
		$this->refreshToken = new RefreshToken('not-refrest-token');
		$this->expires_at = 0;
	}
	
	public function getClientId()
	{
		return $this->$oauthClient->getPublicId();
	}
	/**
	 * @return \Ant\ChateaClient\OAuth2\AccessToken
	 */
	public function getAccessToken() 
	{
		return $this->accesToken;
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
	public function getRefreshToken()
	{
		return $this->refreshToken;
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
		return $this->getAccessToken()->hasExpired();
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

	public function updateToken() 
	{
		
		$tokenRequest = $this->getTokenRequest();
		
		try{
						
			$tokenResponse =  $tokenRequest->withRefreshToken($this->refreshToken);		
			
			$this->accesToken 	= $tokenResponse->getAccessToken();			
			$this->refreshToken = $tokenResponse->getRefreshToken();
			$this->expires_at 	= time() + $tokenResponse->getExpiresIn();	
					
			return $this;			
				
		}catch (TokenRequestException $e){
			throw new AuthenticationException("Error fetching OAuth2 update token, message: " .
					$e->getMessage());
		}	
		return true;		
	}
	
	public function revokeToken() {
	
		$accesToken = $this->getAccessToken();
		
		if(!$accesToken){
			$tokenRequest = $this->getTokenRequest();
			try{

				$this->httpClient->setBaseUrl(IHttpClient::REVOKE_ENDPOINT);
				$response = $this->httpClient->send();				
				$this->accesToken = new AccessToken('not-acces-token');
				$this->refreshToken = new RefreshToken('not-refrest-token');
				$this->expires_at = 0;
				
				return $this;
				
				}catch (HttpClientException $e){
				throw new AuthenticationException("Error fetching OAuth2 revoke token, message: " . 
						$e->getMessage());
			}		
		}
	}	
}