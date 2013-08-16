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

class ChateaOAuth2 extends  ChateaAuth {
		
	
	private  $clientConfig;
	private  $chateaConfig;
	
	public function __construct(
			ClientConfigInterface $client_config, 
			ChateaConfigInterface $chatea_config = null)
	{
		if(!$client_config){
			throw ConfigException(sprintf("missing field client_config is '%s'", $client_config));
		}
		
		$this->clientConfig = $clientConfig;
		
		if(!$chatea_config){
			$this->chateaConfig = ChateaConfig::fromJSONFile('chatea_config.json');			
		}
		
	}
	
	public function authenticate(){
		
		$accesToken = $this->getAccessToken();
		
		if(!$accesToken){
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
	public function setAccessToken(AccessToken $token) {

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
		return $this->getAccessToken()->hasExpired();	
	}
	
	
	private function saveInStore(TokenResponse $tokenResponse ){
		
		$this->chateaConfig->getStorage()->setAccessToken(
				$this->clientConfig->getClientId(),
				$tokenResponse->getAccessToken()
		);
		
		$this->chateaConfig->getStorage()->setRefreshToken(
				$this->clientConfig->getClientId(),
				$tokenResponse->getRefreshToken()
		);		
	}
	
	public function createAuthUrl(Scope $scope){
		throw new \Exception("NON FEITO PENDENTE PARA O LUNS");
		return $this->chateaConfig->getServerEndpoint();
		//    curl -v -H "Accept: application/json" -H "Content-type: application/json" -H "Authorization: Bearer $token" -X GET http://localhost/workspace/apiChatea/web/app_dev.php/api/channel/
	}
}