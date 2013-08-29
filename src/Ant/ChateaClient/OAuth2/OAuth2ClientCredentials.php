<?php
namespace Ant\ChateaClient\OAuth2;

use Ant\ChateaClient\Http\Exception\HttpClientException;

class OAuth2ClientCredentials implements IOAuth2Client
{
	private $client_id;
	private $secret_id;
	private $redirect_uri;
	
	public function __construct($client_id, $secret, $redirect_uri)
	{
		if (!is_string($client_id) || 0 >= strlen($client_id)) {
			throw new HttpClientException("client_id needs to be a non-empty string");
		}
		if (!is_string($secret) || 0 >= strlen($secret)) {
			throw new HttpClientException("secret needs to be a non-empty string");
		}
		if (!is_string($redirect_uri) || 0 >= strlen($redirect_uri)) {
			throw new HttpClientException("redirect_uri needs to be a non-empty string");
		}				
		$this->client_id = $client_id;
		$this->secret_id = $secret;
		$this->redirect_uri = $redirect_uri;
	}
	
	public function getPublicId()
	{
		return $this->client_id;
	}	
	public function getSecret()
	{
		return $this->secret_id;
	}
	public function getRedirectUri()
	{
		return $this->redirect_uri;	
	}
}