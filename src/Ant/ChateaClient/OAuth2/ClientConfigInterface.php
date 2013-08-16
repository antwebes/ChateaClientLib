<?php
namespace Ant\ChateaClient\OAuth2;

interface ClientConfigInterface
{
	public function setClientId($clientId);	
	public function getClientId();	
	public function setClientSecret($clientSecret);	
	public function getClientSecret();
	public function setRedirectUri($redirectUri);	
	public function getRedirectUri();	
	public function setCredentialsInRequestBody($credentialsInRequestBody);
	public function getCredentialsInRequestBody();
	public function setUserId($user_id);
	public function getUserId();
	public function setPasswordId($password_id);
	public function getPasswordId();
}