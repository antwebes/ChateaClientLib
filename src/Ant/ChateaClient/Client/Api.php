<?php
namespace Ant\ChateaClient\Client;
use Ant\ChateaClient\Http\IHttpClient;
use Ant\ChateaClient\Http\HttpClient;
use Ant\ChateaClient\Client\IApi;
use Ant\ChateaClient\Client\ApiException;

/**
 * This class represent the one chat API, this is single abstraction 
 * for all API methods.
 * 
 * This class cannot connect with server, 
 * this responsibility it is class that implement IHttpClient for example HttpClient
 * 
 * @author Xabier Fernández Rodríguez in Ant-Web S.L.
 * 
 * @see Ant\ChateaClient\Http\IHttpClient;
 * @see Ant\ChateaClient\Http\HttpClient;
 * @see Ant\ChateaClient\Client\IApi;
 * @see Ant\ChateaClient\Client\ApiException;
 */
class Api implements IApi 
{

	private $httClient;

	/**
	 * Create a ne API objet 
	 * 
	 * @param IHttpClient 
	 * 			$httpClient the httclient send request to api and retrive response in correct format 
	 */
	public function __construct(IHttpClient $httpClient) {
		if (null === $httpClient) {
			$client = new HttpClient(IHttpClient::SERVER_ENDPOINT);
		}
		$this->httClient = $httpClient;
	}

	/**
	 * This method send http request and return http resposne for
	 * default in json format
	 * 
	 * @param string $response_type
	 * @throws ApiException
	 */
	private function httpClientSend($response_type = 'json') {
		try {
			return $this->httClient->send($response_type);
		} catch (HttpClientException $ex) {
			throw new ApiException($ex->getMessage());
		}
	}
	
	public static function parseRouting($uri, $params = null)
	{
		return HttpClient::parseRouting($uri,$params);
	}
	
	/**
	 * Create a user in server API
	 *
	 * @param IHttpClient $httpClient
	 * @param string $username
	 * @param string $email
	 * @param string $new_password
	 * @param string $repeat_new_password
	 *
	 * @throws ApiException
	 * 		The exception that is thrown when $httpClient is null
	 * 		or $username, $email, $new_password, $repeat_new_password is not valid string
	 * 		or $new_password not equals to $repeat_new_password
	 * 		or API error send for server
	 */
	public static function register(IHttpClient $httpClient, $username, $email,
			$new_password, $repeat_new_password)
	{
	
		if ($httpClient == null) {
			throw new ApiException("httpclient is not null");
		}
		if (!is_string($username) || 0 >= strlen($username)) {
			throw new ApiException("username must be a non-empty string");
		}
		if (!is_string($email) || 0 >= strlen($email)) {
			throw new ApiException("email must be a non-empty string");
		}
		if (!is_string($new_password) || 0 >= strlen($new_password)) {
			throw new ApiException("new_password must be a non-empty string");
		}
	
		if (!is_string($repeat_new_password)
		|| 0 >= strlen($repeat_new_password))
		{
			throw new ApiException(
					"repeat_new_password must be a non-empty string");
		}
	
		if (strcmp($new_password, $repeat_new_password))
		{
			throw new ApiException(
					"the new_password and repeat_new_password isn't equals");
		}
		$data = json_encode(
				array(
						'user_registration' => array('username' => $username,
								'email' => $email,
								'plainPassword' => array(
										'first' => $new_password,
										'second' => $repeat_new_password))));
		$httpClient->setBaseUrl(IHttpClient::SERVER_ENDPOINT);
		$httpClient->addPost(IApi::URI_REGISTER, $data, 'application/json');
	
		try {
			return $httpClient->send();
		} catch (HttpClientException $ex) {
			throw new ApiException($ex->getMessage());
		}
	}
	
	/**
	 * Request reset user password, in the request is mandatory send
	 * username or email (dont work from nelmio api)
	 *
	 * @param IHttpClient $httpClient
	 * @param string $username
	 * @throws ApiException
	 * 		The exception that is thrown when $httpClient is null
	 * 		or $username is not valid string
	 * 		or API error send for server.
	 */
	public static function requestResetpassword(IHttpClient $httpClient, $username) {
		if ($httpClient == null) {
			throw new ApiException("httpclient is not null");
		}
		if (!is_string($username) || 0 >= strlen($username)) {
			throw new ApiException("username must be a non-empty string");
		}
	
		$data = json_encode(array('username' => $username));
	
		$httpClient->setBaseUrl(IHttpClient::SERVER_ENDPOINT);
		$httpClient
		->addPost(IApi::URI_RESETTING_PASSWORD, $data,
				'application/json');
	
		try {
			return $httpClient->send();
		} catch (HttpClientException $ex) {
			throw new ApiException($ex->getMessage());
		}
	}
			
/******************************************************************************/
/*				  				  PROFILE METHODS    	   					  */
/******************************************************************************/
//  GET /api/profile/
	/**
	 * Show a profile of an user
	 */
	 public function showProfile()
	 {
	 	$this->httClient->addGet(IApi::URI_PORFILE_SHOW);
	 	return $this->httpClientSend();	 	
	 }

// 	 PUT|PATCH /api/profile/
	/**
	 * Update a profile of an user
	 * 
	 * @param string $username the new username
	 * 
	 * @param string $email the new email
	 * 
	 * @param String $current_password the password of user in session.
	 * 	if you will change password use: @link #changePassword
	 * 
 	 * @throws ApiException
	 * 		The exception that is thrown when
	 * 		$username, $email, $current_password is not valid string
	 
	 */ 
	public function updateProfile($username, $email, $current_password) 
	{
		if (!is_string($username) || 0 >= strlen($username)) {
			throw new ApiException(
					"ApiException::updateProfile username field needs to be a non-empty string");
		}
		if (!is_string($email) || 0 >= strlen($email)) {
			throw new ApiException(
					"ApiException::updateProfile email field needs to be a non-empty string");
		}
		if (!is_string($current_password) || 0 >= strlen($current_password)) {
			throw new ApiException(
					"ApiException::updateProfile current_password field needs to be a non-empty string");
		}

		$data = array(
				'profile' => array('username' => $username, 'email' => $email,
						'current_password' => $current_password));

		$this->httClient->addPatch(IApi::URI_PORFILE_UPDATE, $data);
		return $this->httpClientSend();
	}
// 	PATCH /api/profile/change-password
	/**
	 * Change user password
	 *
	 * @param string $current_password
	 * @param string $new_password
	 * @param String $repeat_new_password
 	 * @throws ApiException
	 * 		The exception that is thrown when
	 * 		$current_password, $new_password, $new_password, $repeat_new_password is not valid string
	 * 		or $new_password not equals to $repeat_new_password
	 * 		or API error send for server  
	 */
	public function changePassword($current_password, $new_password,
			$repeat_new_password) {
		if (!is_string($current_password) || 0 >= strlen($current_password)) {
			throw new ApiException(
					"ApiException::changePassword() current_password must be a non-empty string");
		}

		if (!is_string($new_password) || 0 >= strlen($new_password)) {
			throw new ApiException(
					"ApiException::changePassword() new_password must be a non-empty string");
		}

		if (!is_string($repeat_new_password)
				|| 0 >= strlen($repeat_new_password)) {
			throw new ApiException(
					"ApiException::changePassword() repeat_new_password must be a non-empty string");
		}

		if (strcmp($new_password, $repeat_new_password)) {
			throw new ApiException(
					"ApiException::changePassword() the new_password and repeat_new_password isn't equals");
		}
		$data = array(
				'change_password' => array(
						'current_password' => $current_password,
						'plainPassword' => array('first' => $new_password,
								'second' => $repeat_new_password)));
		
		$this->httClient->addPatch(IApi::URI_PORFILE_CHANGE_PASSWORD, $data);
		return $this->httpClientSend();
	}
	 	
/******************************************************************************/
/*				  				  CHANNEL METHODS    	   					  */
/******************************************************************************/
	//	GET /api/channels/
	/**
	 * List all the channels
	 */
	 public function showChannels()
	 {
	 	$this->httClient->addGet(IApi::URI_CHANNELS_SHOW);
	 	return $this->httpClientSend();	 	
	 }
	 // 	POST /api/channels/
	 /**
	 * Create a channel
	 *
	 * @param string $name
	 * @param string $title
	 * @param string $description
	 */
	 public function addChanel($name ,$title = '',$description = '')
	 {
	 	if (!is_string($name) || 0 >= strlen($name)) {
	 		throw new ApiException(
	 				"ApiException::addChanel name field needs to be a non-empty string");
	 	}
	 	
	 	$data = array('channel' => array('name' => $name));
	 	if (!empty($title)) {
	 		$data['channel']['title'] = $title;
	 	}
	 	if (!empty($title)) {
	 		$data['channel']['description'] = $description;
	 	}
	 	
	 	$this->httClient->addPost(parseRouting(IApi::URI_CHANNEL_ADD), $data);
	 	return $this->httpClientSend();	 	
	 }
	 // 	PATCH|PUT /api/channels/{id}
	 /**
	 * Update a channel
	 *
	 * @param number $channel_id
	 * @param string $name
	 * @param string $title
	 * @param string $description
	 */
	 public function updateChannel($channel_id, $name, $title = '', $description = '')
	 {
	 	if (!is_numeric($channel_id) || 0 >= $channel_id) {
	 		throw new ApiException(
	 				"ApiException::updateChannel channel_id field should be positive integer");
	 	}
	 	if (!is_string($name) || 0 >= strlen($name)) {
	 		throw new ApiException(
	 				"ApiException::updateChannel name field needs to be a non-empty string");
	 	}
	 	
	 	$data = array('channel' => array('name' => $name));
	 	
	 	if (!empty($title)) {
	 		$data['channel']['title'] = $title;
	 	}
	 	if (!empty($title)) {
	 		$data['channel']['description'] = $description;
	 	}
	 	
	 	$this->httClient->addPatch(self::parseRouting(IApi::URI_CHANNEL_UPDATE,$channel_id),$data);
	 	
	 	return $this->httpClientSend();	 	
	 }
	 // 	DELETE /api/channels/{id};
	 /**
	 * Delete a channel
	 *
	 * @param number $channel_id
	 */
	 public function delChannel($channel_id)
	 {
	 	if (!is_numeric($channel_id) || 0 >= $channel_id) {
	 		throw new ApiException(
	 				"ApiException::updateChannel channel_id field should be positive integer");
	 	}
	 	$this->httClient->addDelete(self::parseRouting(IApi::URI_CHANNEL_UPDATE,$channel_id));
	 	return $this->httpClientSend();	 	
	 }
	 // 	GET /api/channels/{id}
	 /**
	 * Show a channel by id
	 *
	 * @param number $id
	 */
	 public function showChannel($channel_id)
	 {
	 	if (!is_numeric($channel_id) || 0 >= $channel_id) {
	 		throw new ApiException(
	 				"ApiException::showChannel channel_id field should be positive integer");
	 	}
	 	$this->httClient->addGet(self::parseRouting(IApi::URI_CHANNEL_SHOW, $channel_id));
	 	return $this->httpClientSend();	 	
	 }
	 // 	GET /api/channels/{id}/fans
	 /**
	 * Show all users fans of a channel
	 *
	 * @param number $channel_id
	 */
	 public function showChannelFans($channel_id)
	 {
	 	if (!is_numeric($channel_id) || 0 >= $channel_id) {
	 		throw new ApiException(
	 				"ApiException::showChannelFans channel_id field should be positive integer");
	 	}
	 	$this->httClient->addGet(self::parseRouting(IApi::URI_CHANNEL_FANS_SHOW,$channel_id));
	 	return $this->httpClientSend();	 	
	 }
	 //	GET /api/users/{id}/channels
	 /**
	  * Show list channels create of an user
	  *
	  * @param number $user_id
	  * 		The user id that I'll like get channels
	  */
	  public function showUserChannels($user_id)
	  {
	  if (!is_numeric($user_id) || 0 >= $user_id) {
	  throw new ApiException(
	  		"ApiException::showChannelsByUser user_id field should be positive integer");
	 	}
	 	$this->httClient->addGet(self::parseRouting(IApi::URI_USER_CHANNELS_SHOW,$user_id));
	 	 	return $this->httpClientSend();
	 }	 
	 //	GET /api/users/{id}/channelsFan
	 /**
	 * Show all channels fan of an user
	 *
	 * @param number $user_id
	 */
	 public function showUserChannelsFan($user_id)
	 {
	 	if (!is_numeric($user_id) || 0 >= $user_id) {
	 		throw new ApiException(
	 				"ApiException::showChannelsFan user_id field should be positive integer");
	 	}
	 	$this->httClient->addGet(self::parseRouting(IApi::URI_USER_CHANNEL_FAN_SHOW,$user_id));
	 	return $this->httpClientSend();	 	
	 }
	 //	POST /api/users/{user_id}/channels/{channel_id}/fans
	 /**
	 * Make user a channel fan
	 *
	 * @param number $user_id
	 */
	 public function addUserChannelFan($channel_id, $user_id)
	 {
	 	if (!is_numeric($channel_id) || 0 >= $channel_id) {
	 		throw new ApiException(
	 				"ApiException::addChannelFan channel_id field should be positive integer");
	 	}	 	
	 	if (!is_numeric($user_id) || 0 >= $user_id) {
	 		throw new ApiException(
	 				"ApiException::addChannelFan user_id field should be positive integer");
	 	}
	 	
	 	$this->httClient->addPost(self::parseRouting(IApi::URI_USER_CHANNEL_FAN_ADD,array($user_id,$channel_id)));
	 	return $this->httpClientSend();
	 		 	
	 }
	 //	DELETE /api/users/{user_id}/channels/{channel_id}/fans
	 /**
	 * Remove user as a channel fan.
	 *
	 * @param number $channel_id
	 * @param number $user_id
	 */
	 public function delUserChannelFan($channel_id, $user_id)
	 {
	 	if (!is_numeric($channel_id) || 0 >= $channel_id) {
	 		throw new ApiException(
	 				"ApiException::addChannelFan channel_id field should be positive integer");
	 	}
	 	if (!is_numeric($user_id) || 0 >= $user_id) {
	 		throw new ApiException(
	 				"ApiException::addChannelFan user_id field should be positive integer");
	 	}
	 	 
	 	$this->httClient->addDelete(self::parseRouting(IApi::URI_USER_CHANNEL_FAN_ADD,array($user_id,$channel_id)));
	 	return $this->httpClientSend();	 	
	 }


/******************************************************************************/
/******************************************************************************/


}
