<?php
namespace Ant\ChateaClient\OAuth2;

class OAuth2ClientCredentials extends OAuth2Client
{

	public function __construct($client_id, $secret, $redirect_uri)
	{
		if (!is_string($client_id) || 0 >= strlen($client_id)) {
			throw new OAuth2ClientException("client_id needs to be a non-empty string",$this);
		}
		if (!is_string($secret) || 0 >= strlen($secret)) {
			throw new OAuth2ClientException("secret needs to be a non-empty string",$this);
		}
		if (!is_string($redirect_uri) || 0 >= strlen($redirect_uri)) {
			throw new OAuth2ClientException("redirect_uri needs to be a non-empty string",$this);
		}				
		parent::__construct($client_id,$secret, $redirect_uri);
	}
}