<?php
namespace Ant\ChateaClient\Client;

use Ant\ChateaClient\OAuth2\TokenRequest;
use Ant\ChateaClient\OAuth2\AccessToken;
use Ant\ChateaClient\OAuth2\RefreshToken;
use Ant\ChateaClient\OAuth2\TokenResponse;
use Ant\ChateaClient\OAuth2\TokenException;
use Ant\ChateaClient\OAuth2\Scope;
use Ant\ChateaClient\Http\IHttpClient;

class OAuth2 extends  IAuthentication 
{
		
	
	private $clientConfig;
	private $httpClient;
	private $accesToken;
	private $refreshToken;
	private $expiresIn;
	    
	protected function __construct(IOAuth2Client $oauthClient, IHttpClient $httpClient)
	{
		if(!$oauthClient){
			throw ConfigException(sprintf("missing field client_config is '%s'", $oauthClient));
		}
		
		$this->clientConfig = $clientConfig;
		
		if (!is_string($username) || 0 >= strlen($username)) {
			throw new ChateaApiException("username must be a non-empty string");
		}
		 
		if (!is_string($password) || 0 >= strlen($password)) {
			throw new ChateaApiException("password must be a non-empty string");
		}
		
		$this->username = $username;
		$this->password = $password;
		
		if(!$chateaConfig){
			$this->chateaConfig = ChateaConfig::fromJSONFile('chatea_config.json');			
		}
	}
	
	public function authenticate(){
		

		$tokenRequest = $this->getTokenRequest();
		
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
		
		$tokenRequest = $this->getTokenRequest();
		
		try{
						
			$tokenResponse =  $tokenRequest->withRefreshToken($this->refreshToken);		
			
			$this->accesToken 	= $tokenResponse->getAccessToken();
			$this->refreshToken = $tokenResponse->getRefreshToken();
			$this->expiresIn 	= $tokenResponse->getExpiresIn();	
					
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
			$tokenRequest = $this->getTokenRequest();
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
	public function isAuthenticationExpired() 
	{
		return time() > $this->expiresIn;	
	}
	
	protected function getTokenRequest(){
		return new TokenRequest(
				new Client($this->chateaConfig->getTokenEndpoint()), 
				$this->clientConfig, 
				$this->chateaConfig					
		);
	}
	public function getChateaConfig(){
		return $this->chateaConfig;
	}
	public function getClientConfig(){
		return $this->clientConfig;
	}
}