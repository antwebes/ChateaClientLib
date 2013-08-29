<?php
namespace Ant\ChateaClient\OAuth2;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Ant\ChateaClient\Http\IHttpClient;
use Ant\ChateaClient\OAuth2\Ant\ChateaClient\OAuth2;
use Ant\ChateaClient\Http\Exception\HttpClientException;

class TokenRequest
{
    private $httpClient;
    private $clientConfig;
	
    public function __construct(IOAuth2Client $oauthClient, IHttpClient $httpClient){

    	if(!$oauthClient){
    		throw new TokenRequestException("OAuth2Client is not null");
    	}    	
    	if(!$client){
    		throw new TokenRequestException("Client is not null");
    	}    	
    	if (!is_string($this->httpClient->getUrl()) || 0 >= strlen($this->httpClient->getUrl())) {
    		$this->httpClient->setBaseUrl(IHttpClient::TOKEN_ENDPOINT);
    	}
    	
    	$this->clientConfig = $oauthClient;
        $this->httpClient = $client;
    }

    public function withAuthorizationCode($auth_code){
    	if (!is_string($this->oauthClient->getSecret()) || 0 >= strlen($this->oauthClient->getSecret())) {
    		throw new TokenRequestException("The secret in your OAuth2Client needs to be a non-empty string");
    	}
    	if (!is_string($this->oauthClient->getRedirectUri()) || 0 >= strlen($this->oauthClient->getRedirectUri())) {
    		throw new TokenRequestException("The rediret uri in your OAuth2Client needs to be a non-empty string");
    	} 
    	if (!is_string($auth_code) || 0 >= strlen($auth_code)) {
    		throw new TokenRequestException("The auth code needs to be a non-empty string");
    	}    	   	    	
    	$data = array (
    			"grant_type" => 'authorization_code',
    			"code" => $auth_code,    			
    			"client_id"=>$this->clientConfig->getPublicId(),
    			"client_secret"=>$this->clientConfig->getSecret(),
    			"redirect_uri"=>$this->clientConfig->getRedirectUri()
    	); 
		
    	try {
			$data_json = $this->httpClient->send(true);
			return TokenResponse::fromArray($data_json);
		} catch (HttpClientException $e) {
			throw new TokenRequestException($e->getMessage());
		}
		
    }
    public function withUserCredentials($username, $password){
    	if (!is_string($this->oauthClient->getSecret()) || 0 >= strlen($this->oauthClient->getSecret())) {
    		throw new TokenRequestException("The secret in your OAuth2Client needs to be a non-empty string");
    	}
    	    	
    	if (!is_string($username) || 0 >= strlen($username)) {
    		throw new TokenRequestException("username must be a non-empty string");
    	}
    	
    	if (!is_string($password) || 0 >= strlen($password)) {
    		throw new TokenRequestException("password must be a non-empty string");
    	}

    	$data = array (
    			"username" => $username,
    			"password" => $password,
    			"grant_type" => "password",
    			"client_id"=>$this->clientConfig->getPublicId(),
    			"client_secret"=>$this->clientConfig->getSecret()
    	);    	
    	try {
    		$data_json = $this->httpClient->send(true);
    		return TokenResponse::fromArray($data_json);
    	} catch (HttpClientException $e) {
    		throw new TokenRequestException($e->getMessage());
    	}
    }

    public function withClientCredentials()
    {

    	if (!is_string($this->oauthClient->getSecret()) || 0 >= strlen($this->oauthClient->getSecret())) {
    		throw new TokenRequestException("The secret in your OAuth2Client needs to be a non-empty string");
    	}
    	if (!is_string($this->oauthClient->getRedirectUri()) || 0 >= strlen($this->oauthClient->getRedirectUri())) {
    		throw new TokenRequestException("The rediret uri in your OAuth2Client needs to be a non-empty string");
    	} 
    	
    	$data = array (
    			"grant_type" => "client_credentials",
    			"client_id"=>$this->clientConfig->getPublicId(),
    			"client_secret"=>$this->clientConfig->getSecret(),
    			"redirect_uri"=>$this->clientConfig->getRedirectUri()
    	);
    	try {
    		$data_json = $this->httpClient->send(true);
    		return TokenResponse::fromArray($data_json);
    	} catch (HttpClientException $e) {
    		throw new TokenRequestException($e->getMessage());
    	}    	
    }
    public function withRefreshToken(RefreshToken $refreshToken)
    {
        if (!is_string($this->oauthClient->getSecret()) || 0 >= strlen($this->oauthClient->getSecret())) {
    		throw new TokenRequestException("The secret in your OAuth2Client needs to be a non-empty string");
    	}
		if(!$refreshToken){
			throw new TokenRequestException("The refreshToken is not null");
		}
    	
    	$data = array (
    			"grant_type" => "refrest_token",
    			'refrest_token'=>$refreshToken->getValue(),
    			"client_id"=>$this->clientConfig->getPublicId(),
    			"client_secret"=>$this->clientConfig->getSecret()
    	);
    	try {
    		$data_json = $this->httpClient->send(true);
    		return TokenResponse::fromArray($data_json);
    	} catch (HttpClientException $e) {
    		throw new TokenRequestException($e->getMessage());
    	} 
    } 
}
