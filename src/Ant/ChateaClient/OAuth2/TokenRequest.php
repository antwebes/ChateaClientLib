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
	
    public function withAuthorizationCode($authorizationCode)
    {
    	// FIXME: This function isn't implemented on server
    	return $this->withPasswordCredentials();
    }

    public function withRefreshToken(RefreshToken $refreshToken)
    {
    	// FIXME: This function isn't implemented on server
    	return $this->withPasswordCredentials();
    }

    public function withPasswordCredentials(){
    	$p = array (
    			"username" => $this->clientConfig->getUserId(),
    			"password" => $this->clientConfig->getPasswordId(),
    			"grant_type" => "password",
    			"client_id"=>$this->clientConfig->getClientId(),
    			"client_secret"=>$this->clientConfig->getClientSecret()
    	);    	
        if ($this->clientConfig->getCredentialsInRequestBody()) {
            // provide credentials in the POST body
            $request = $this->httpClient->post($this->chateaConfig->getTokenEndpoint())->addPostFields($p);
        } else {
            $request = $this->httpClient->get($this->chateaConfig->getTokenEndpoint(),array(),
            			array('query' =>$p)
            		);            
        }
        $request->addHeader('Accept','application/json');
        try {
            $response = $request->send();
            // FIXME: what if no JSON?
            return TokenResponse::fromArray($response->json());
        } catch (ClientErrorResponseException $e) {
        	throw new TokenException($e->getMessage(),$e->getCode());
            // FIXME: if authorization code request fails? What should we do then?!
            // whenever there is 4xx error, we return FALSE, if some other error
            // occurs we just pass along the Exception...
            return false;
        }
    }
    public function revokeToken (){
    	// FIXME: This function isn't implemented on server
    	return null;
    }
}
