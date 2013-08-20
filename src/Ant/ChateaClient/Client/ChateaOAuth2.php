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
		
	
	private  $clientConfig;
	private  $chateaConfig;
	
	public function __construct(
			ClientConfigInterface $clientConfig, 
			ChateaConfigInterface $chateaConfig = null)
	{
		if(!$clientConfig){
			throw ConfigException(sprintf("missing field client_config is '%s'", $clientConfig));
		}
		
		$this->clientConfig = $clientConfig;
		
		if(!$chateaConfig){
			$this->chateaConfig = ChateaConfig::fromJSONFile('chatea_config.json');			
		}
		
	}
	
	public function authenticate($username, $password){
		
	    if (!is_string($username) || 0 >= strlen($username)) {
    		throw new ChateaApiExceptionException("username must be a non-empty string");
    	}
    	
    	if (!is_string($password) || 0 >= strlen($password)) {
    		throw new ChateaApiExceptionException("password must be a non-empty string");
    	}

    	if($this->isAccessTokenExpired()){
			$tokenRequest = new TokenRequest(
					new Client($this->chateaConfig->getTokenEndpoint()), 
					$this->clientConfig, 
					$this->chateaConfig					
			);
			
			try{
				$tokenResponse =  $tokenRequest->withPasswordCredentials($username, $password);
				$this->saveInStore($tokenResponse);
									
			}catch (TokenException $e){
				throw new ChateaAuthException("Error fetching OAuth2 access token, message: " .
						$e->getMessage());
			}catch (\Exception $e){
				throw new ChateaAuthException("Error fetching OAuth2 access token, message: " .
						$e->getMessage());
			}					
    	}
		return true;
	}

	public function refreshToken(RefreshToken $refreshToken) 
	{
		$tokenRequest = new TokenRequest(
				new Client($this->chateaConfig->getTokenEndpoint()), 
				$this->clientConfig, 
				$this->chateaConfig					
		);
		try{
		
			$tokenResponse =  $tokenRequest->withRefreshToken($refreshToken);		
			$this->saveInStore($tokenResponse);
				
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
					new Client($this->chateaConfig->getTokenEndpoint()), 
					$this->clientConfig, 
					$this->chateaConfig					
		 	);
			try{

				$tokenResponse =  $tokenRequest->withRefreshToken($refreshToken);		

				//delete in store				
				$this->chateaConfig->getStorage()->deleteAccessToken(
						$this->clientConfig->getClientId()
				);
				
				$this->chateaConfig->getStorage()->deleteRefreshToken(
						$this->clientConfig->getClientId()
				);
			
			}catch (\Exception $e){
				throw new ChateaAuthException("Error fetching OAuth2 access token, message: " . 
						$e->getMessage());
			}
						
		}
		
		return true;
	}	
	/**
	 * @return AccessToken
	 */
	public function getAccessToken() {	
		return $this->chateaConfig->getStorage()->getAccessToken(
					$this->clientConfig->getClientId()
		);
	}
	/**
	 * @param AccessToken $token
	 * @throws ChateaAuthException
	 */
	public function setAccessToken(AccessToken $token) 
	{

		if ($token == null) {
			throw new ChateaAuthException('Could not nullable the token');
		}
		
		// Save in store
		$this->chateaConfig->getStorage()->setAccessToken(
				$this->clientConfig->getClientId(), 
				$tokenResponse->getAccessToken()
		);
	}
	
	/**
	 * Returns if the access_token is expired.
	 * @return bool Returns True if the access_token is expired.
	 */
	public function isAccessTokenExpired() 
	{
		
		return $this->getAccessToken()?$this->getAccessToken()->hasExpired():true;	
	}
	
	
	private function saveInStore(TokenResponse $tokenResponse )
	{
		
		$this->chateaConfig->getStorage()->setAccessToken(
				$this->clientConfig->getClientId(),
				$tokenResponse->getAccessToken()
		);
		
		$this->chateaConfig->getStorage()->setRefreshToken(
				$this->clientConfig->getClientId(),
				$tokenResponse->getRefreshToken()
		);		
	}

	public function getChateaConfig(){
		return $this->chateaConfig;
	}
	public function getClientConfig(){
		return $this->clientConfig;
	}
}