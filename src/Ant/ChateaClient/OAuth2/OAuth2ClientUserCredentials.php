<?php
namespace Ant\ChateaClient\OAuth2;

class OAuth2ClientUserCredentials extends OAuth2Client 
{
	private $username;
	private $password;
	
	public function __construct($client_id, $secret, $username, $password)
	{
		if (!is_string($client_id) || 0 >= strlen($client_id)) {
			throw new HttpClientException("client_id needs to be a non-empty string");
		}
		if (!is_string($secret) || 0 >= strlen($secret)) {
			throw new HttpClientException("secret needs to be a non-empty string");
		}
		if (!is_string($username) || 0 >= strlen($username)) {
			throw new HttpClientException("username needs to be a non-empty string");
		}
		if (!is_string($password) || 0 >= strlen($password)) {
			throw new HttpClientException("password needs to be a non-empty string");
		}								
		$this->username = $username;
		$this->password = $password;
		
		parent::__construct($client_id, $secret, '');
	}
	
	public function getUsername()
	{
		return $this->username;
	}
	
	public function getPassword()
	{
		return $this->password;
	}
}
