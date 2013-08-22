<?php
namespace Ant\ChateaClient\Client;

use Guzzle\Http\Client;
use Ant\ChateaClient\Client\ChateaAuth;
use Ant\ChateaClient\OAuth2\ConfigException;
use Ant\ChateaClient\OAuth2\TokenRequest;
use Ant\ChateaClient\OAuth2\ChateaConfigInterface;
use Ant\ChateaClient\OAuth2\ClientConfigInterface;
use Ant\ChateaClient\OAuth2\AccessToken;
use Ant\ChateaClient\OAuth2\RefreshToken;
use Ant\ChateaClient\OAuth2\TokenResponse;
use Ant\ChateaClient\OAuth2\Scope;
use Ant\ChateaClient\OAuth2\TokenException;
class ChateaOAuth2 extends  ChateaAuth {
		
	
	private $clientConfig;
	private $chateaConfig;
	private $accesToken;
	private $refreshToken;
	private $expiresIn;
	private $username;
	private $password;
	    
	public function __construct(
			ClientConfigInterface $clientConfig, 
			$username, 
			$password,
			ChateaConfigInterface $chateaConfig = null)
	{
		if(!$clientConfig){
			throw ConfigException(sprintf("missing field client_config is '%s'", $clientConfig));
		}
		
		$this->clientConfig = $clientConfig;
		
		if (!is_string($username) || 0 >= strlen($username)) {
			throw new ChateaApiExceptionException("username must be a non-empty string");
		}
		 
		if (!is_string($password) || 0 >= strlen($password)) {
			throw new ChateaApiExceptionException("password must be a non-empty string");
		}
		
		$this->username = $username;
		$this->password = $password;
		
		if(!$chateaConfig){
			$this->chateaConfig = ChateaConfig::fromJSONFile('chatea_config.json');			
		}
	}
	
	public function authenticate(){
		

		$tokenRequest = new TokenRequest(
				new Client($this->chateaConfig->getTokenEndpoint()), 
				$this->clientConfig, 
				$this->chateaConfig					
		);
		
		try{
			
			$tokenResponse =  $tokenRequest->withPasswordCredentials($this->username, $this->password);

			$this->accesToken 	= $tokenResponse->getAccessToken();			
			
			$this->refreshToken = $tokenResponse->getRefreshToken();		

			$this->expiresIn 	= $tokenResponse->getExpiresIn();
			
			return $this;
			
		}catch (TokenException $e){
			throw new ChateaAuthException("Error fetching OAuth2 access token, message: " .
					$e->getMessage());
		}catch (\Exception $e){
			throw new ChateaAuthException("Error fetching OAuth2 access token, message: " .
					$e->getMessage());
		}					
		return true;
	}

	public function updateToken() 
	{
		$tokenRequest = new TokenRequest(
				new Client($this->chateaConfig->getTokenEndpoint()), 
				$this->clientConfig, 
				$this->chateaConfig					
		);
		try{
						
			$tokenResponse =  $tokenRequest->withRefreshToken($this->refreshToken);		
			
			$this->accesToken 	= $tokenResponse->getAccessToken();
			$this->refreshToken = $tokenResponse->getRefreshToken();
			$this->expiresIn = time() + $tokenResponse->getExpiresIn();	
					
			return $this;			
				
		}catch (\Exception $e){
			throw new ChateaAuthException("Error fetching OAuth2 access token, message: " .
					$e->getMessage());
		}	
		return true;		
	}
	
	public function revokeToken() {
	
		$accesToken = $this->getAccessToken();
		
		if(!$accesToken){
			$tokenRequest = new TokenRequest(
				 
					$this->clientConfig, 
					$this->chateaConfig					
		 	);
			try{

				$client = new Client($this->chateaConfig->getRevokeEndpoint());
				$response = $client->get('/')->send();
				
				$this->accesToken = null;
				$this->refreshToken = null;		
				$this->expiresIn = time();
				
				return $this;
				
			}catch (\Exception $e){
				throw new ChateaAuthException("Error fetching OAuth2 access token, message: " . 
						$e->getMessage());
			}		
		}
	}	
	/**
	 * @return AccessToken
	 */
	public function getAccessToken() {	
		return $this->accesToken;
	}
	public function getRefreshToken(){
		return $this->refreshToken;
	}
	/**
	 * Returns true if the access_token is expired.
	 * 
	 * @return bool Returns True if the access_token is expired.
	 */
	public function isAccessTokenExpired() 
	{
		return time() > $this->expiresIn;	
	}
	
	
	public function getChateaConfig(){
		return $this->chateaConfig;
	}
	public function getClientConfig(){
		return $this->clientConfig;
	}
}