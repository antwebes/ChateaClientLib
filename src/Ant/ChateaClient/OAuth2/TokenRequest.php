<?php
namespace Ant\ChateaClient\OAuth2;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Exception\ClientErrorResponseException;

class TokenRequest
{
    private $httpClient;
    private $clientConfig;
	private $chateaConfig;
    public function __construct(Client $client, 
    			ClientConfigInterface $clientConfig, 
    			ChateaConfigInterface $chateaConfig
	){
    	
        $this->httpClient = $client;
        $this->clientConfig = $clientConfig;
        $this->chateaConfig = $chateaConfig;
    }
	
    public function withPasswordCredentials($username, $password){
    	if (!is_string($username) || 0 >= strlen($username)) {
    		throw new TokenRequestException("username must be a non-empty string");
    	}
    	
    	if (!is_string($password) || 0 >= strlen($password)) {
    		throw new TokenRequestException("password must be a non-empty string");
    	}

    	$p = array (
    			"username" => $username,
    			"password" => $password,
    			"grant_type" => "password",
    			"client_id"=>$this->clientConfig->getClientId(),
    			"client_secret"=>$this->clientConfig->getClientSecret()
    	);    	
    	
        if ($this->clientConfig->getCredentialsInRequestBody()) {
            // provide credentials in the POST body
            $request = $this->httpClient->post($this->chateaConfig->getTokenEndpoint())->addPostFields($p);
        }else {
            $request = $this->httpClient->get($this->chateaConfig->getTokenEndpoint(),array(),
            			array('query' =>$p)
            		);            
        }
        
        $request->addHeader('Accept','application/json');
        
        try {
            $response = $request->send();
            return TokenResponse::fromArray($response->json());
        } catch (ClientErrorResponseException $e) {
        	throw new TokenRequestException($e->getResponse()->getBody());
        }
    }
    
    public function withAuthorizationCode($authorizationCode)
    {
    	// FIXME: This function isn't implemented on server
    	throw new TokenRequestException("this method not implemented yet");
    }
    
    public function withRefreshToken(RefreshToken $refreshToken)
    {
    	// FIXME: This function isn't implemented on server
    	throw new TokenRequestException("this method not implemented yet");
    }    
}
