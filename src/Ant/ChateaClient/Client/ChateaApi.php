<?php
namespace Ant\ChateaClient\Client;

use Guzzle\Http\Client; 
use fkooman\Guzzle\Plugin\BearerAuth\BearerAuth;
use fkooman\Guzzle\Plugin\BearerAuth\Exception\BearerErrorResponseException;
use Guzzle\Http\Exception\BadResponseException;


class ChateaApi
{

	private $chateaAut;
	private $client; 
	private $clientConfig;
	private $username;
	private $password;
	
	const URI_CHANNELS 						= 'api/channel/';
	const URI_CREATE_CHANNEL 				= 'api/channel/';
	const URI_DELETE_CHANNEL 				= 'api/channel/';
	const URI_GET_CHANNEL 					= 'api/channel/';
	const URI_GET_PORFILE 					= 'api/profile/';
	const URI_PATCH_PORFILE					= 'api/profile/edit';
	const URI_PUT_PORFILE					= 'api/profile/edit';
	const URI_PATCH_PORFILE_CHANGE_PASSWORD = 'api/profile/change-password';
	const URI_GET_ALL_USERS 				= 'api/user/list';
	const URI_GET_USER_ME 					= 'api/user/me';
	const URI_DELETE_USER 					= "api/user";
	const URI_POST_REGISTER					= "register";  
	
	public function __construct(ClientConfig $clientConfig, $username, $password){
		if(!$clientConfig){
			throw new ChateaApiException(sprintf("missing field client_config is '%s'", $clientConfig));
		}
				
		$this->clientConfig = $clientConfig;
		
		if (!is_string($username) || 0 >= strlen($username)) {
			throw new ChateaApiExceptionException("username must be a non-empty string");
		}
		 
		if (!is_string($password) || 0 >= strlen($password)) {
			throw new ChateaApiExceptionException("password must be a non-empty string");
		}
		
		$this->chateaAut = new ChateaOAuth2($clientConfig);
		
		$this->username = $username;
		
		$this->password = $password;

		$this->client = new Client($this->getServerEndpoint());
		
		try {			
												
			$this->authenticate();		
					
		}catch (\ChateaAuthException $e){
			throw new ChateaApiException($e->getMessage());
		}		
	}
	
	/**
	 * Retrieve the current session token. 
	 * If the session requested caducadao new one.
	 * 
	 * @return string token value
	 */
	public function getBearerToken (){
		 
		return $this->chateaAut->getAccessToken()->getValue();
	}
	
	/**
	 * Retrieves the API server url.
	 * 
	 * @return string the API server url  
	 */
	protected function getServerEndpoint(){
		 
		return $this->chateaAut->getChateaConfig()->getServerEndpoint();
	}
	
	/**
	 * Retrieves the http client.
	 * 
	 * @return Guzzle\Http\Client
	 */
	protected function getClient(){
		return $this->client;
	}
	
	/**
	 * Retrieves the default client header Accept 
	 * 
	 * @return string default header Accept for example: application/json
	 */
	protected function getHeaderAccept(){
		return $this->clientConfig->getAccept();
	}
	
	/**
	 *	Responsible for obtaining authentication. 
	 *	If the session has expired, it make requests to the server for a 
	 *	new autorizacón 
	 */
	private function authenticate(){		
		$this->chateaAut->authenticate($this->username,$this->password);		
	}
		
	/**
	 * List all the channels
	 *  
	 * @throws ChateaApiException This exception is run, if error in client
	 *  
	 * @return json whith all channles 
	 */
	public function getAllChannels()
	{
		
		$this->authenticate();
				
		$headers = array('Accept'=>$this->getHeaderAccept(),
						 'Authorization'=> sprintf("Bearer %s", $this->getBearerToken()));
						
		$request = $this->getClient()->get(ChateaApi::URI_CHANNELS, $headers);
						
		try {
			return $request->send()->getBody();	
		} catch (BearerErrorResponseException $e) {
			throw new ChateaApiException("ChateaApi: ".$e->getMessage());
		} catch (BadResponseException $e) {
			throw new ChateaApiException("ChateaApi: ".$e->getMessage());
		}		
	}
	
	
	/**
	 * Create a new channel entity
	 * 
	 * @param string $name name of channel. This field is required.  
	 * @param string $title	title of channel. 
	 * @param string $description descripcion of channel.
	 * @throws ChateaApiException this 
	 * @return Ambigous <\Guzzle\Http\EntityBodyInterface, string, \Guzzle\Http\EntityBody, \Guzzle\Http\EntityBody>
	 */
	public function createChannel($name,  $title = '', $description = '')
	{
		
		$this->authenticate();
				
		$headers = array('Accept'=>$this->getHeaderAccept(),
						 'Content-type'=>'application/json',
						 'Authorization'=> sprintf("Bearer %s", $this->getBearerToken()));
				
		
		$data = json_encode(array(
					'channel'=>array(
							'name'=>$name,
							'title'=>$title,
							'description'=>$description
							)
						)	
		);
		
		$request = $this->getClient()->post(ChateaApi::URI_CHANNELS, $headers, $data);
		 
		try {
		
			return $request->send()->getBody();
			
		} catch (BearerErrorResponseException $e) {
			throw new ChateaApiException($e->getResponse()->getBody());
		} catch (BadResponseException $e) {			
			throw new ChateaApiException($e->getResponse()->getBody());
		}					
	}
	
	/**
	 * Deletes a Channel entity.
	 * 
	 * @param numeric $id
	 * @throws ChateaApiException
	 */
	public function deleteChannel($id){
		
		if(!$id || !is_numeric($id) || is_null($id)){
			throw new ChateaApiException('ChateaApi: '. sprintf("missing field 'id' is '%s'", $id));
		}
		
		$this->authenticate();
		
		$headers = array('Accept'=>$this->getHeaderAccept(),				
						 'Authorization'=> sprintf("Bearer %s", $this->getBearerToken()));
						
		$request = $this->getClient()->delete(self::URI_DELETE_CHANNEL.'/'.$id, $headers);

		try {			
			return $request->send()->getBody();				
		} catch (BearerErrorResponseException $e) {
			throw new ChateaApiException($e->getResponse()->getBody());
		} catch (BadResponseException $e) {				
			throw new ChateaApiException($e->getResponse()->getBody());
		}		
	}
	
	/**
	 * 
	 * Show a channel by id
	 * 
	 * @param numeric $id
	 * @param string $name
	 * @param string $title
	 * @param string $description
	 */
	public function getChannel($id)
	{
		if(!$id || !is_numeric($id) || is_null($id)){
			throw new ChateaApiException('ChateaApi: '. sprintf("missing field 'id' is '%s'", $id));
		}		
		
		$this->authenticate();
		
		$headers = array('Accept'=>$this->getHeaderAccept(),
						 'Authorization'=> sprintf("Bearer %s", $this->getBearerToken()));
						
		$request = $this->getClient()->get(self::URI_GET_CHANNEL.'/'.$id, $headers);
		
		try {
			return $request->send()->getBody();
		} catch (BearerErrorResponseException $e) {
			throw new ChateaApiException($e->getResponse()->getBody());
		} catch (BadResponseException $e) {
			throw new ChateaApiException($e->getResponse()->getBody());
		}		
	}

	/**
	 * Show a profile of an user
	 */
	public function getProfile(){

		$this->authenticate();
		
		$headers = array('Accept'=>$this->getHeaderAccept(),
						 'Authorization'=> sprintf("Bearer %s", $this->getBearerToken()));			
		
		$request = $this->getClient()->get(self::URI_GET_PORFILE, $headers);
				
		try {
			return $request->send()->getBody();
		} catch (BearerErrorResponseException $e) {
			throw new ChateaApiException($e->getResponse()->getBody());
		} catch (BadResponseException $e) {
			throw new ChateaApiException($e->getResponse()->getBody());
		}		
	}

	/**
	 * Change user password
	 *             
	 * @param string $new_password
	 * @param string $repeat_new_password
	 */
    public function changePassword($current_password, $new_password, $repeat_new_password)
    {
    	$this->authenticate();
    	
    	if (!is_string($current_password) || 0 >= strlen($current_password)) {
    		throw new ChateaApiExceptionException("current_password must be a non-empty string");
    	}
    	
    	if (!is_string($new_password) || 0 >= strlen($new_password)) {
    		throw new ChateaApiException("new_password must be a non-empty string");
    	}
    	 
    	if (!is_string($repeat_new_password) || 0 >= strlen($repeat_new_password)) {
    		throw new ChateaApiException("repeat_new_password must be a non-empty string");
    	}
    	 
    	if(strcmp($new_password, $repeat_new_password)){
    		throw new ChateaApiException("the new_password and repeat_new_password isn't equals");
    	}
    	
    	$headers = array('Accept'=>$this->getHeaderAccept(),
    			'Content-type'=>'application/json',
    			'Authorization'=> sprintf("Bearer %s", $this->getBearerToken()));
    	    	
    	
    	$data = json_encode(array(
    			'change_password'=>array(
    					'current_password'=>$current_password,
    					'plainPassword'=>array('first'=>$new_password,
    											'second'=>$repeat_new_password	
    					)
    			)
    		)
    	);
    	 
    	$request = $this->getClient()->patch(self::URI_PATCH_PORFILE_CHANGE_PASSWORD, $headers, $data);
    	  	
    	try {
    		$responseBody = $request->send()->getBody();
    		$this->password = $new_password;    		
    		return $responseBody;
    	} catch (BearerErrorResponseException $e) {
    		throw new ChateaApiException($e->getResponse()->getBody());
    	} catch (BadResponseException $e) {
    		throw new ChateaApiException($e->getResponse()->getBody());
    	}  
    }

    /**
     * Edits an existing User entity
     * 
     * @param string $username
     * @param string $email
     * @param string $current_password the user 
     */
    public function editProfile($username, $email, $current_password){

    	if (!is_string($username) || 0 >= strlen($username)) {
    		throw new ChateaApiExceptionException("username must be a non-empty string");
    	}

    	if (!is_string($current_password) || 0 >= strlen($current_password)) {
    		throw new ChateaApiExceptionException("email must be a non-empty string");
    	}    	  
    	
    	if (!is_string($current_password) || 0 >= strlen($current_password)) {
    		throw new ChateaApiExceptionException("current_passwor must be a non-empty string");
    	}

    	$this->authenticate();
    	
    	$headers = array('Accept'=>$this->getHeaderAccept(),
    			'Content-type'=>'application/json',
    			'Authorization'=> sprintf("Bearer %s", $this->getBearerToken()));
    	 
    	    	 
    	$data = json_encode(array(
    			'profile'=>array(
    					'username'=>$username,
    					'email'=>$email,
    					'current_password'=>$current_password
    			)
    		)
    	);
    	
    	$request = $this->getClient()->patch(self::URI_PATCH_PORFILE, $headers, $data);
    	 
    	
    	try {
    		return $request->send()->getBody();
    	} catch (BearerErrorResponseException $e) {
    		throw new ChateaApiException($e->getResponse()->getBody());
    	} catch (BadResponseException $e) {
    		throw new ChateaApiException($e->getResponse()->getBody());
    	}    	
    }
	/**
	 * Get all the users
	 */
    public function getAllUser()
    {
    	$this->authenticate();
    	
    	$headers = array('Accept'=>$this->getHeaderAccept(),
    			'Authorization'=> sprintf("Bearer %s", $this->getBearerToken()));
    	
    	
    	
    	$request = $this->getClient()->get(self::URI_GET_ALL_USERS, $headers);    	  	   	    	
    	 
    	try {
    		return $request->send()->getBody();
    	} catch (BearerErrorResponseException $e) {
    		throw new ChateaApiException($e->getResponse()->getBody());
    	} catch (BadResponseException $e) {
    		throw new ChateaApiException($e->getResponse()->getBody());
    	}    	
    }
    
    public function getWhoami(){
    	$this->authenticate();
    	 
    	$headers = array('Accept'=>$this->getHeaderAccept(),
    			'Authorization'=> sprintf("Bearer %s", $this->getBearerToken()));
    	 
    	 
    	 
    	$request = $this->getClient()->get(self::URI_GET_USER_ME, $headers);
    	
    	try {
    		return $request->send()->getBody();
    	} catch (BearerErrorResponseException $e) {
    		throw new ChateaApiException($e->getResponse()->getBody());
    	} catch (BadResponseException $e) {
    		throw new ChateaApiException($e->getResponse()->getBody());
    	}    	
    	
    } 


    public function deleteUser($id){

    	if(!$id || !is_numeric($id) || is_null($id)){
    		throw new ChateaApiException('ChateaApi: '. sprintf("missing field 'id' is '%s'", $id));
    	}    	
    	
    	$this->authenticate();
    	
    	$headers = array('Accept'=>$this->getHeaderAccept(),
    			'Authorization'=> sprintf("Bearer %s", $this->getBearerToken()));
    	
    	
    	
    	$request = $this->getClient()->delete(self::URI_DELETE_USER.'/'.$id, $headers);
    	 
    	try {
    		return $request->send()->getBody();
    	} catch (BearerErrorResponseException $e) {
    		throw new ChateaApiException($e->getResponse()->getBody());
    	} catch (BadResponseException $e) {
    		throw new ChateaApiException($e->getResponse()->getBody());
    	}    	
    }
     
    public static function register(ChateaConfig $config,  $username, $email,$new_password, $repeat_new_password){
    	if(!$config){
    		throw new ChateaApiExceptionException("config must be a null");
    	}
    	if (!is_string($username) || 0 >= strlen($username)) {
    		throw new ChateaApiExceptionException("username must be a non-empty string");
    	}
    	if (!is_string($email) || 0 >= strlen($email)) {
    		throw new ChateaApiExceptionException("email must be a non-empty string");
    	}    	 
    	if (!is_string($new_password) || 0 >= strlen($new_password)) {
    		throw new ChateaApiException("new_password must be a non-empty string");
    	}
    	
    	if (!is_string($repeat_new_password) || 0 >= strlen($repeat_new_password)) {
    		throw new ChateaApiException("repeat_new_password must be a non-empty string");
    	}
    	
    	if(strcmp($new_password, $repeat_new_password)){
    		throw new ChateaApiException("the new_password and repeat_new_password isn't equals");
    	}    	
    	
    	$headers = array('Accept'=>'application/json','Content-type'=>'application/json');

    	$data = json_encode(array(
    			'user_registration'=>array(
    					'username'=>$username,
    					'email'=>$email,
    					'plainPassword'=>array(
    						'first'=>$new_password,
    						'second'=>$repeat_new_password				
    					)
    			)
    		)
    	);

    	$client =  new Client($config->getServerEndpoint()); 	

    	$request = $client->post(self::URI_POST_REGISTER, $headers, $data);
	
    	try {
    		ld($request->getUrl());
    		    		
    		return $request->send()->getBody();
    	} catch (BearerErrorResponseException $e) {
    		throw new ChateaApiException($e->getResponse()->getBody());
    	} catch (BadResponseException $e) {
    		throw new ChateaApiException($e->getResponse()->getBody());
    	}    	
    }

}
