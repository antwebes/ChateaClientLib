<?php
namespace Ant\ChateaClient\OAuth2;

interface ClientConfigInterface
{
	const ACCEPT_JSON  = "application/json";
	const ACCEPT_XML  = "application/xml";
		
	public function setClientId($clientId);	
	public function getClientId();	
	public function setClientSecret($clientSecret);	
	public function getClientSecret();
	public function setRedirectUri($redirectUri);	
	public function getRedirectUri();	
	public function setCredentialsInRequestBody($credentialsInRequestBody);
	public function getCredentialsInRequestBody();
	public function getAccept();
	public function setAccept($header);
	
	public static function getAcceptHeader($name = "json");
}