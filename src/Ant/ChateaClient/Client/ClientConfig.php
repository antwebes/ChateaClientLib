<?php
namespace Ant\ChateaClient\Client;

use Ant\ChateaClient\OAuth2\ClientConfigInterface;
use Ant\ChateaClient\OAuth2\ConfigException;

/**
 * This class represents de config parameters for a client. 
 * 
 * @author ant3
 *
 *@example
 *	$clientConfig = new ClientConfig( 
 *		array(
 *				"client_id" => "2_63gig21vk9gc0kowgwwwgkcoo0g84kww00c4400gsc0k8oo4ks",
 *				"client_secret" => "202mykfu3ilckggkwosgkoo8g40w4wws0k0kooo488wo048k0w",
 *				"redirect_uri" =>   "http://www.chateagratis.net"
 *				"user_id"		 => user@ant.com
 *				"password_id"	=>	mySuperSecretPassword 
 *		)
 * );
 * 	
 * 	`client_secrets.json` file format:
 * 	    
 *    // chateagratis.net
 *    $cahteaGratisClientConfig = new ChateaConfig(
 *        json_decode(file_get_contents("client_secrets.json"), true)
 *    );
 */
class ClientConfig implements ClientConfigInterface {
	
	// VSCHAR     = %x20-7E
	const REGEXP_VSCHAR = '/^(?:[\x20-\x7E])*$/';
		
	/* The client identifier issued to the client during the registration process */
	private $clientId;
	/*The client secret. The client MAY omit the parameter if the client secret is an empty string.*/
	private $clientSecret;
	/* the url rediret */
	private $redirectUri;
	/* true: provide credentials client_id and secret in the POST body */
	private $credentialsInRequestBody;
	/* is required in Authorization for Resource Owner Password Credentials Grant (section-4.3.2)*/
	private $userId;
	/* is required in Authorization for Resource Owner Password Credentials Grant (section-4.3.2)*/
	private $passwordId;
	
	/**
	 * Create object ChateaConfig.   
	 * 
	 * @param array $data
	 * @throws ConfigException
	 */
	public function __construct(array $data)
	{
		foreach (array('client_id', 'user_id','password_id') as $key) {
			if (!array_key_exists($key, $data)) {
				throw new ConfigException(sprintf("missing field '%s'", $key));
			}
		}				
		$this->setClientId($data['client_id']);
				
		$clientSecret = array_key_exists('client_secret', $data) ? $data['client_secret'] : null;
		$this->setClientSecret($clientSecret);
		
		$redirectUri = array_key_exists('redirect_uri', $data) ? $data['redirect_uri'] : null;
		$this->setRedirectUri($redirectUri);
		
		$credentialsInRequestBody = array_key_exists('credentials_in_request_body', $data) ? $data['credentials_in_request_body'] : false;
		$this->setCredentialsInRequestBody($credentialsInRequestBody);		
		
		$this->setUserId($data['user_id']);
		$this->setPasswordId($data['password_id']);
	}
	
	public static function fromJSONFile($filename)
	{			
		if(!file_exists($filename)){
			throw new ConfigException(sprintf("file '%s' not exist ", $filename));
		}
	
		$clientConfig = new static(
				json_decode(file_get_contents($filename), true)
		);
		return $clientConfig;
	}	
	public static function fromArray(array $data)
	{
		foreach (array('client_id', 'user_id','password_id') as $key) {
			if (!array_key_exists($key, $data)) {
				throw new ConfigException(sprintf("missing field '%s'", $key));
			}
		}
	
		return new static($data);
	}	
	public function setClientId($clientId)
	{
		if (!is_string($clientId) || 0 >= strlen($clientId)) {
			throw new ConfigException("client_id must be a non-empty string");
		}
		$this->validateUserData($clientId);
		$this->clientId = $clientId;
	}
	public function getClientId()
	{
		return $this->clientId;
	}
	
	public function setClientSecret($clientSecret)
	{
		if (null !== $clientSecret) {
			if (!is_string($clientSecret) || 0 >= strlen($clientSecret)) {
				throw new ConfigException("client_secret must be a non-empty string or null");
			}
			$this->validateUserData($clientSecret);
		}
		$this->clientSecret = $clientSecret;
	}
	
	public function getClientSecret()
	{
		return $this->clientSecret;
	}
	
	public function setRedirectUri($redirectUri)
	{
		if (null !== $redirectUri) {
			$this->validateUris($redirectUri);
		}
		$this->redirectUri = $redirectUri;
	}
	
	public function getRedirectUri()
	{
		return $this->redirectUri;
	}
	
	public function setCredentialsInRequestBody($credentialsInRequestBody)
	{
		$this->credentialsInRequestBody = (bool) $credentialsInRequestBody;
	}
	
	public function getCredentialsInRequestBody()
	{
		return $this->credentialsInRequestBody;
	}
	public function setUserId($user_id){
		if (null !== $user_id) {
			if (!is_string($user_id) || 0 >= strlen($user_id)) {
				throw new ConfigException("user_id must be a non-empty string or null");
			}
			$this->validateUserData($user_id);
		}
		$this->userId = $user_id;		
	}
	public function getUserId(){
		return $this->userId;
	}
	public function setPasswordId($password_id){	
		if (!is_string($password_id) || 0 >= strlen($password_id)) {
			throw new ConfigException("password_id must be a non-empty string or null");
		}	
		$this->passwordId = $password_id;
	}
	public function getPasswordId()
	{
		return $this->passwordId;
	}
	public function __toString()
	{
		return '{"clientId": '.$this->getClientId().',
				 "clientSecret": '.$this->getClientSecret().',
				 "redirectUri": '.$this->getRedirectUri().',
				 "credentialsInRequestBody": '.$this->getCredentialsInRequestBody().',
				 "userId": '.$this->getUserId().', 
				 "passwordId": '.$this->getPasswordId().'
				 }';
	}
	private function validateUserData($userPass)
	{
		if (1 !== preg_match(self::REGEXP_VSCHAR, $userPass)) {
			throw new ConfigException("invalid characters in client_id or client_secret");
		}
	}
	
	private function validateUris($endpointUri)
	{
		if (!is_string($endpointUri) || 0 >= strlen($endpointUri)) {
			throw new ConfigException("uri must be a non-empty string");
		}
		if (false === filter_var($endpointUri, FILTER_VALIDATE_URL)) {
			throw new ConfigException("uri must be valid URL");
		}
		// not allowed to have a fragment (#) in it
		if (null !== parse_url($endpointUri, PHP_URL_FRAGMENT)) {
			throw new ConfigException("uri must not contain a fragment");
		}
	}
		
}