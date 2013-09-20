<?php
namespace Ant\ChateaClient\OAuth2;

use Ant\ChateaClient\OAuth2\OAuth2Client;
use Ant\ChateaClient\OAuth2\OAuth2ClientException;
class OAuth2ClientUserCredentials extends OAuth2Client 
{
	private $username;
	private $password;
	
	public function __construct($client_id, $secret, $username, $password, $redirect_uri = '')
	{
		if (!is_string($client_id) || 0 >= strlen($client_id)) {
			throw new OAuth2ClientException("client_id needs to be a non-empty string", $this);
		}
		if (!is_string($secret) || 0 >= strlen($secret)) {
			throw new OAuth2ClientException("secret needs to be a non-empty string", $this);
		}
		if (!is_string($username) || 0 >= strlen($username)) {
			throw new OAuth2ClientException("username needs to be a non-empty string",$this);
		}
		if (!is_string($password) || 0 >= strlen($password)) {
			throw new OAuth2ClientException("password needs to be a non-empty string",$this);
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
