<?php
namespace Ant\ChateaClient\OAuth2;

class OAuth2ClientAuthCode extends OAuth2Client
{
	private $code;
	
	public function __construct($client_id, $secret, $authCode, $redirect_uri)
	{
		if (!is_string($client_id) || 0 >= strlen($client_id)) {
			throw new OAuth2ClientException("client_id needs to be a non-empty string",$this);
		}
		if (!is_string($secret) || 0 >= strlen($secret)) {
			throw new OAuth2ClientException("secret needs to be a non-empty string",$this);
		}
		if (!is_string($authCode) || 0 >= strlen($authCode)) {
			throw new OAuth2ClientException("authCode needs to be a non-empty string",$this);
		}
		if (!is_string($redirect_uri) || 0 >= strlen($redirect_uri)) {
			throw new OAuth2ClientException("redirect_uri needs to be a non-empty string",$this);
		}								
		$this->code = $authCode;
		
		parent::__construct($client_id, $secret, $redirect_uri);
	}
	
	public function getAuthCode()
	{
		return $this->code;
	}
}
