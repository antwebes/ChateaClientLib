<?php
namespace Ant\ChateaClient\OAuth2;

use Ant\ChateaClient\OAuth2\TokenResponse;
use Ant\ChateaClient\OAuth2\TokenException;
use Ant\ChateaClient\OAuth2\TokenRequestException;
use Ant\ChateaClient\Http\IHttpClient;
use Ant\ChateaClient\Http\HttpClientException;

class TokenRequest
{
    private $httpClient;
    private $oauthClient;
	
    public function __construct(IOAuth2Client $oauthClient, IHttpClient $httpClient)
    {

    	if(!$oauthClient){
    		throw new TokenRequestException("OAuth2Client is not null");
    	}    	
    	if(!$httpClient){
    		throw new TokenRequestException("HttpClient is not null");
    	}    	    	
    	if (!is_string($httpClient->getUrl()) || 0 >= strlen($httpClient->getUrl())) {
    		$this->httpClient->setBaseUrl(IHttpClient::TOKEN_ENDPOINT);
    	}    	
    	$this->oauthClient = $oauthClient;
        $this->httpClient = $httpClient;
    }

    private function getTokenResponse($data)
    {    	
    	$this->httpClient->addPostData($data);
    	 
    	try {
    		$data_json = $this->httpClient->send(true);
    		ld($data_json);  		
    		return TokenResponse::formJson($data_json);
    	} catch (HttpClientException $e) {      		
    		throw new TokenRequestException($e->getMessage(),-1,$e);
    	}    	
    }
    public function withAuthorizationCode($auth_code)
    {
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
    			"client_id"=>$this->oauthClient->getPublicId(),
    			"client_secret"=>$this->oauthClient->getSecret(),
    			"redirect_uri"=>$this->oauthClient->getRedirectUri()
    	); 
		
		return $this->getTokenResponse($data);
		
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
    			"client_id"=>$this->oauthClient->getPublicId(),
    			"client_secret"=>$this->oauthClient->getSecret()
    	);    	
    	
		return $this->getTokenResponse($data);
    }

    public function withClientCredentials()
    {

    	if (!is_string($this->oauthClient->getSecret()) || 0 >= strlen($this->oauthClient->getSecret())) {
    		throw new TokenRequestException("The secret in your OAuth2Client needs to be a non-empty string");
    	}
    	    	
    	$data = array (
    			"grant_type" => "client_credentials",
    			"client_id"=>$this->oauthClient->getPublicId(),
    			"client_secret"=>$this->oauthClient->getSecret()    			
    	);		
    	return $this->getTokenResponse($data);
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
    			"grant_type" => "refresh_token",
    			'refresh_token'=>$refreshToken->getValue(),
    			"client_id"=>$this->oauthClient->getPublicId(),
    			"client_secret"=>$this->oauthClient->getSecret()
    	);
    	
		return $this->getTokenResponse($data);
    } 
}
