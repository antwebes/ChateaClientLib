<?php
namespace Ant\ChateaClient\OAuth2;
							  
class OAuth2Client implements IOAuth2Client
{
	private $client_id;
	private $secret_id;
	private $redirect_uri;
	
	public function __construct($client_id, $secret = '', $redirect_uri = '')
	{
		if (!is_string($client_id) || 0 >= strlen($client_id)) {
			throw new OAuth2ClientException("client_id needs to be a non-empty string",$this);
		}
		$this->client_id = $client_id;
		$this->secret_id = $secret;
		if(!empty($redirect_uri) && !self::isValidUrl($redirect_uri))
		{	
			throw new OAuth2ClientException("redirect_uri needs to be a valid uri ",$this);
		}
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
	private static function isValidUrl($url)
	{
		return preg_match('/^(http:\/\/|https:\/\/|ftp:\/\/|ftps:\/\/|)?[a-z0-9_\-]+[a-z0-9_\-\.]+\.[a-z]{2,4}(\/+[a-z0-9_\.\-\/]*)?$/i',$url);
	}
}