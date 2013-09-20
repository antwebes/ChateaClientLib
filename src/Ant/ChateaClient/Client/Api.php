<?php
namespace Ant\ChateaClient\Client;
use Ant\ChateaClient\Http\IHttpClient;
use Ant\ChateaClient\Http\HttpClient;
use Ant\ChateaClient\Http\HttpClientException;
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

	private $httpClient;

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
		$this->httpClient = $httpClient;
		$this->httpClient->setBaseUrl(IHttpClient::SERVER_ENDPOINT);
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
			return $this->httpClient->send($response_type);
		} catch (HttpClientException $ex) {
			throw new ApiException($ex->getMessage(),$ex,$ex->getCode(),$ex);
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
			throw new ApiException($ex->getMessage(),$ex,$ex->getCode(),$ex);
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
			throw new ApiException($ex->getMessage(),$ex,$ex->getCode(),$ex);
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
	 	$this->httpClient->addGet(IApi::URI_PORFILE_SHOW);
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

		$this->httpClient->addPatch(IApi::URI_PORFILE_UPDATE, $data);
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
		
		$this->httpClient->addPatch(IApi::URI_PORFILE_CHANGE_PASSWORD, $data);
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
	 	$this->httpClient->addGet(IApi::URI_CHANNELS_SHOW);
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
	 	
	 	$this->httpClient->addPost(parseRouting(IApi::URI_CHANNEL_ADD), $data);
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
	 	
	 	$this->httpClient->addPatch(self::parseRouting(IApi::URI_CHANNEL_UPDATE,$channel_id),$data);
	 	
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
	 	$this->httpClient->addDelete(self::parseRouting(IApi::URI_CHANNEL_UPDATE,$channel_id));
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
	 	$this->httpClient->addGet(self::parseRouting(IApi::URI_CHANNEL_SHOW, $channel_id));
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
	 	$this->httpClient->addGet(self::parseRouting(IApi::URI_CHANNEL_FANS_SHOW,$channel_id));
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
	 	$this->httpClient->addGet(self::parseRouting(IApi::URI_USER_CHANNELS_SHOW,$user_id));
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
	 	$this->httpClient->addGet(self::parseRouting(IApi::URI_USER_CHANNEL_FAN_SHOW,$user_id));
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
	 	
	 	$this->httpClient->addPost(self::parseRouting(IApi::URI_USER_CHANNEL_FAN_ADD,array($user_id,$channel_id)));
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
	 	 
	 	$this->httpClient->addDelete(self::parseRouting(IApi::URI_USER_CHANNEL_FAN_ADD,array($user_id,$channel_id)));
	 	return $this->httpClientSend();	 	
	 }


/******************************************************************************/
/*			  			FRIENDSHIP METHODS    	   					  		  */
/******************************************************************************/	
	
// 	GET api/users/{id}/friends
	/**
	 * returns the friends of user
	 */
	 public function showFriends($user_id)
	 {
	 	$this->httpClient->addGet(self::parseRouting(IApi::URI_USER_FRIENDS_SHOW,$user_id));
	 	return $this->httpClientSend();	 	
	 }
//	 POST api/users/{id}/friends
	 /**
	 * sends a friendship request to a given user
	 *
	 * @param number $user_id
	 * @param number $friend_id
	 */
	 public function addFirend($user_id,$friend_id)
	 {
	 	if (!is_numeric($user_id) || 0 >= $user_id) {
	 		throw new ApiException(
	 				"ApiException::addFirend user_id field should be positive integer");
	 	}
	 	if (!is_numeric($friend_id) || 0 >= $friend_id) {
	 		throw new ApiException(
	 				"ApiException::addFirend user_id field should be positive integer");
	 	}	 	
	 	$this->httpClient->addPost(
	 			self::parseRouting(IApi::URI_USER_FRIEND_ADD,$user_id), 
	 			array('user_id' => $friend_id)
	 	);
	 	return $this->httpClientSend();	 	
	 }
// 	 GET api/users/{id}/friends/pending
	 /**
	 * returns the friends that they are pending accept by an user
	 */
	 public function showFriendshipsPending($user_id)
	 {
	 	if (!is_numeric($user_id) || 0 >= $user_id) {
	 		throw new ApiException(
	 				"ApiException::showFriendshipsPending user_id field should be positive integer");
	 	}
	 	$this->httpClient->addGet(self::parseRouting(IApi::URI_USER_FRIENDSHIPS_PENDING_SHOW,$user_id));
	 	return $this->httpClientSend(); 	
	 }
// 	 GET api/users/{id}/friends/requests
	 /**
	 * returns the requests friendships that one user doesn't have accepted
	 *
	 * @param number $user_id
	 */
	 public function showFriendshipsRequest ($user_id)
	 {
	 	if (!is_numeric($user_id) || 0 >= $user_id) {
	 		throw new ApiException(
	 				"ApiException::showFriendshipsRequest user_id field should be positive integer");
	 	}	 	
	 	$this->httpClient->addGet(self::parseRouting(IApi::URI_USER_FRIENDSHIPS_REQUEST_SHOW,$user_id));
	 	return $this->httpClientSend();	 	
	 }
// 	 PUT /api/users/{id}/friends/requests/{user_accept_id}
	 /**
	 * accepts a friendship request
	 *
	 * @param number $user_id
	 * @param number $user_accept_id
	 */
	 public function addFriendshipRequest($user_id,$user_accept_id)
	 {
	 	if (!is_numeric($user_id) || 0 >= $user_id) {
	 		throw new ApiException(
	 				"ApiException::addFriendshipRequest user_id field should be positive integer");
	 	}
	 	if (!is_numeric($user_accept_id) || 0 >= $user_accept_id) {
	 		throw new ApiException(
	 				"ApiException::addFriendshipRequest user_accept_id field should be positive integer");
	 	}	 
	 		
	 	$this->httpClient->addPut(self::parseRouting(IApi::URI_USER_FRIENDSHIPS_ACCEPTS,array($user_id,$user_accept_id)));
	 	return $this->httpClientSend();	 	
	 }
// 	 DELETE /api/users/{id}/friends/requests/{user_decline_id}
	 /**
	 * Decline a friendship request
	 *
	 * @param number $user_id
	 * @param number $user_decline_id
	 */
	 public function delFriendshipRequest($user_id,$user_decline_id)
	 {
	 	if (!is_numeric($user_id) || 0 >= $user_id) {
	 		throw new ApiException(
	 				"ApiException::delFriendshipRequest user_id field should be positive integer");
	 	}
	 	if (!is_numeric($user_decline_id) || 0 >= $user_decline_id) {
	 		throw new ApiException(
	 				"ApiException::delFriendshipRequest user_decline_id field should be positive integer");
	 	}
	 		 	
	 	$this->httpClient->addDelete(self::parseRouting(IApi::URI_USER_FRIENDSHIPS_DECLINE, array($user_id,$user_decline_id)));
	 	return $this->httpClientSend();	 	
	 }
// 	  DELETE api/users/{id}/friends/{user_delete_id}
	 /**
	 * Deletes a friendship
	 *
	 * @param number $user_id
	 * @param number $user_delete_id
	 */
	 public function delFriend($user_id, $user_delete_id)
	 {
	 	if (!is_numeric($user_id) || 0 >= $user_id) {
	 		throw new ApiException(
	 				"ApiException::delFriend user_id field should be positive integer");
	 	}
	 	
	 	if (!is_numeric($user_delete_id) || 0 >= $user_delete_id) {
	 		throw new ApiException(
	 				"ApiException::delFriend user_delete_id field should be positive integer");
	 	}	 	
	 	$this->httpClient->addDelete(self::parseRouting(IApi::URI_USER_FRIENDSHIPS_DEL, array($user_id, $user_delete_id)));
	 	return $this->httpClientSend();	 	
	 }

/******************************************************************************/
/*			  				ME METHODS	    	   					  		  */
/******************************************************************************/
// 	 GET /api/me
	
	/**
	 * Get my user of session
	 */
	public function whoami()
	{
		$this->httpClient->addGet(IApi::URI_ME_SHOW);
		return $this->httpClientSend();		
	}
	/**
	 * Get my user of session
	 */	
	public function showMe()
	{
		return $this->whoami();
	}	  
//	  DELETE /api/me/
   /**
	* Delete my user
	*/
	public function delMe()
	{
		$this->httpClient->addDelete(IApi::URI_ME_DEL);
		return $this->httpClientSend();		
	}
	
/******************************************************************************/
/*			  				PHOTO METHODS    	   					  		  */
/******************************************************************************/
	 
//	GET /api/photo/{id}
  /**
	* Show a photo
	*
	* @param number $photo_id
	*/
	public function showPhoto($photo_id)
	{
		if (!is_numeric($photo_id) || 0 >= $photo_id) {
			throw new ApiException(
					"ApiException::showPhoto photo_id field should be positive integer");
		}		
		$this->httpClient->addGet(self::parseRouting(IApi::URI_PHOTO_SHOW,$photo_id));
		return $this->httpClientSend();		
	}
// 	  GET /api/photo/{id}/votes
	/**
	 * Show a vote of a photo
	 *
	 * @param number $photo_id
	 */
	public function showPhotoVotes($photo_id)
	{
		if (!is_numeric($photo_id) || 0 >= $photo_id) {
			throw new ApiException(
					"ApiException::showPhotoVotes photo_id field should be positive integer");
		}
		$this->httpClient->addGet(self::parseRouting(IApi::URI_PHOTO_VOTES_SHOW,$photo_id));
		return $this->httpClientSend();		
	}
// 	  GET /api/user/{id}/photos
	/**
	 * List all photos of an user
	 *
	 * @param number $user_id
	 */
	public function showPhotos($user_id)
	{
		if (!is_numeric($user_id) || 0 >= $user_id) {
			throw new ApiException(
					"ApiException::showPhotos user_id field should be positive integer");
		}
		$this->httpClient->addGet(self::parseRouting(IApi::URI_USER_PHOTO_SHOW,$photo_id));
		return $this->httpClientSend();		
	}
// 	  POST /api/users/{id}/photo
	/**
	 * Create a photo
	 *  
	 * @param number $user_id
     * @param string $imageTile 
	 * @param string $imageFile
	 */
	 public function addPhoto($user_id, $imageTile, $imageFile)
	 {
	 	if (!is_numeric($user_id) || 0 >= $user_id) {
	 		throw new ApiException(
	 				"ApiException::addPhoto user_id field should be positive integer");
	 	}	 	
	 	if (!is_string($imageTile) || 0 >= strlen($imageTile)) {
	 		throw new ApiException(
	 				"ApiException::addPhoto imageTile field needs to be a non-empty string");
	 	}
	 	if (!file_exists($imageFile)) {
	 		throw new ApiException(
	 				sprintf("ApiException::addPhoto imageFile: '%s'  not exists",
	 						$imageFile));
	 	}
	 	$this->httpClient->addPost(
	 			self::parseRouting(self::URI_USER_PHOTO_ADD,$user_id), 
	 			array('title' => $imageTile)
	 	);	 	
	 	
	 	$this->httpClient->addPostFile(imageFile,'image');
	 	
	 	return $this->httpClientSend();			
	 }
// 	  GET /api/users/{id}/vote
	/**
	 * Show all votes of an user
	 *
	 * @param number $user_id
	 */
	public function showUserVotes($user_id)
	{
		if (!is_numeric($user_id) || 0 >= $user_id) {
			throw new ApiException(
					"ApiException::addPhoto user_id field should be positive integer");
		}		
		$this->httpClient->addGet(self::parseRouting(IApi::URI_USER_PHOTO_VOTES_SHOW,$photo_id));
		return $this->httpClientSend();		
	}
// 	  POST /api/users/{id}/vote	
	  /**
	  * Create a vote
	  *
	  * @param number $user_id
	  * @param number $photo_id
	  * @param number $core
	   */
	public function addPhotoVote($user_id,$photo_id,$core)
	{
		if (!is_numeric($user_id) || 0 >= $user_id) {
			throw new ApiException(
					"ApiException::addPhoto user_id field should be positive integer");
		}		
		if (!is_numeric($photo_id) || 0 >= $photo_id) {
			throw new ApiException(
					"ApiException::addPhoto photo_id field should be positive integer");
		}
		if (!is_numeric($core) || 0 >= $core) {
			throw new ApiException(
					"ApiException::addPhoto core field should be positive integer");
		}
				
		$data = array('vote'=>array('photo'=>$photo_id,'score>'=>$core));
		 
		$this->httpClient->addPost(
				self::parseRouting(self::URI_USER_PHOTO_VOTES_ADD,$user_id),
				$data
		);		
		return $this->httpClientSend();		
	}
// 	  DELETE /api/users/{id}/vote/{photo_id}
  /**
	* Delete a vote
	* @param number $user_id
	* @param number $photo_id
	*/
	public function delPhotoVote($user_id, $photo_id)
	{
		if (!is_numeric($user_id) || 0 >= $user_id) {
			throw new ApiException(
					"ApiException::delPhotoVote user_id field should be positive integer");
		}
		if (!is_numeric($photo_id) || 0 >= $photo_id) {
			throw new ApiException(
					"ApiException::delPhotoVote photo_id field should be positive integer");
		}
		$this->httpClient->addDelete(self::parseRouting(IApi::URI_USER_PHOTO_VOTES_DEL,array($user_id, $photo_id)));
		return $this->httpClientSend();
		
	}
// 	  DELETE /api/users/{user_id}/photo/{photo_id}
  /**
    * Delete a photo
    * @param number $user_id
	* @param number $photo_id
    */
	public function delPhoto($user_id,$photo_id)
	{
		if (!is_numeric($user_id) || 0 >= $user_id) {
			throw new ApiException(
					"ApiException::delPhoto user_id field should be positive integer");
		}
		if (!is_numeric($photo_id) || 0 >= $photo_id) {
			throw new ApiException(
					"ApiException::delPhoto photo_id field should be positive integer");
		}
		$this->httpClient->addDelete(self::parseRouting(IApi::URI_USER_PHOTO_DEL,array($user_id, $photo_id)));
		return $this->httpClientSend();		
	}	

	
/******************************************************************************/
/*			  				THREADS METHODS    	   					  		  */
/******************************************************************************/
//       POST /api/users/{id}/threads
	/**
	 *
	 * Creates a thread
	 *
	 * @param number $user_id
	 * @param string $recipient
	 * @param string $subject
	 * @param string $body
	 */
	 public function addThread($user_id, $recipient, $subject, $body)
	 {
	 	if (!is_numeric($user_id) || 0 >= $user_id) {
	 		throw new ApiException(
	 				"ApiException::addThread user_id field should be positive integer");
	 	}
	 		 	
	 	if (!is_string($recipient) || 0 >= strlen($recipient)) {
	 		throw new ApiException(
	 				"ApiException::addThread recipient field needs to be a non-empty string");
	 	}
	 	if (!is_string($subject) || 0 >= strlen($subject)) {
	 		throw new ApiException(
	 				"ApiException::addThread subject field needs to be a non-empty string");
	 	}
	 	if (!is_string($body) || 0 >= strlen($body)) {
	 		throw new ApiException(
	 				"ApiException::addThread body field needs to be a non-empty string");
	 	}
	 	
	 	$data = array(
	 			'message' => array('recipient' => $recipient,
	 					'subject' => $subject, 'body' => $body));
	 	
	 	$this->httpClient->addPost(
	 			self::parseRouting(IAPI::URI_THREAD_ADD,$user_id), 
	 			$data
	 	);
	 	
	 	return $this->httpClientSend();	 	
	 }
//       GET /api/users/{id}/threads/inbox
	/**
	* Lists threads with messages had been sent by one user
	 *
	 * @param number $user_id
	 */
	 public function showThreadsInbox($user_id)
	 {
	 	if (!is_numeric($user_id) || 0 >= $user_id) {
	 		throw new ApiException(
	 				"ApiException::showThreadsInbox user_id field should be positive integer");
	 	}

	 	$this->httpClient->addGet(self::parseRouting(IApi::URI_THREAD_INBOX_SHOW,$user_id));
	 	
	 	return $this->httpClientSend();	 		 	
	 }
//       GET /api/users/{id}/threads/sent
	 /**
	 * Messages list in inbox one user.
	 *
	 * @param number $user_id
	 */
	 public function showThreadsSent($user_id)
	 {
	 	if (!is_numeric($user_id) || 0 >= $user_id) {
	 		throw new ApiException(
	 				"ApiException::showThreadsSent user_id field should be positive integer");
	 	}
	 	
	 	$this->httpClient->addGet(self::parseRouting(IApi::URI_THREAD_SENT_SHOW,$user_id));
	 	 
	 	return $this->httpClientSend();	 	
	 }
//       GET /api/threads/{thread_id}
	 /**
	 * The messages list a given thread
	 *
	 * @param number $thread_id
	 */
	 public function showThread($thread_id)
	 {
	 	if (!is_numeric($thread_id) || 0 >= $thread_id) {
	 		throw new ApiException(
	 				"ApiException::showThread thread_id field should be positive integer");
	 	}
	 	 
	 	$this->httpClient->addGet(self::parseRouting(IApi::URI_THREAD_SHOW,$showThread));
	 	
	 	return $this->httpClientSend();	 	
	 }
//       POST /api/users/{id}/threads/{thread_id}
	 /**
	 * Replies a message to a given thread
	 *
	 * @param number $user_id
	 * @param number $thread_id
	 * @param string $body
	 */
	 public function addThreadMessage($user_id,$thread_id,$body)
	 {
	 	if (!is_numeric($user_id) || 0 >= $user_id) {
	 		throw new ApiException(
	 				"ApiException::addThreadMessage user_id field should be positive integer");
	 	}
	 	
	 	if (!is_string($thread_id) || 0 >= strlen($thread_id)) {
	 		throw new ApiException(
	 				"ApiException::addThreadMessage thread_id field needs to be a non-empty string");
	 	}

	 	if (!is_string($body) || 0 >= strlen($body)) {
	 		throw new ApiException(
	 				"ApiException::addThreadMessage body field needs to be a non-empty string");
	 	}
	 	 
	 	$data = array('message' => array('subject' => $subject, 'body' => $body));
	 	 
	 	$this->httpClient->addPost(
	 			self::parseRouting(IApi::URI_THREAD_MESSAGE_ADD,array($user_id,$thread_id)),
	 			$data
	 	);
	 	 
	 	return $this->httpClientSend();	 	
	 }
//		DELETE /api/threads/{thread_id}
	 /**
	 * Deletes a thread
	 *
	 * @param number $thread_id
	 */
	 public function delThread($thread_id)
	 {
	 	if (!is_string($thread_id) || 0 >= strlen($thread_id)) {
	 		throw new ApiException(
	 				"ApiException::delThread thread_id field needs to be a non-empty string");
	 	}	

	 	$this->httpClient->addDelete(self::parseRouting(IApi::URI_THREAD_DEL,$thread_id));	 	
	 	return $this->httpClientSend();	 	
	 }

/******************************************************************************/
/*			  				USER METHODS    	   					  		  */
/******************************************************************************/
// 		GET /api/users
	 /**
	  * Get all the users
	  */
	  public function who()
	  {
	  	$this->httpClient->addGet(IAPi::URI_USER_SHOW);
	  	return $this->httpClientSend();	  		
	  }
	  /**
	   * Get all the users
	   */	  
	  public function showUsers()
	  {
	  	return $this->who();
	  }
// 		GET /api/users/{id}
	 /**
	 * Get the user
	  * @param number $user_id
	  */
	  public function showUser($user_id)
	  {
	  	if (!is_numeric($user_id) || 0 >= $user_id) {
	  		throw new ApiException(
	  				"ApiException::showUser user_id field should be positive integer");
	  	}	  	
	  	$this->httpClient->addGet(self::parseRouting(IAPi::URI_USER_SHOW,$user_id));
	  	return $this->httpClientSend();	  	
	  }
// 		GET /api/users/{id}/blocked
	  /**
	  * Get blocked users of the session user
	  * @param number $user_id
	  */
	  public function showUsersBlocked($user_id)
	  {
	  	if (!is_numeric($user_id) || 0 >= $user_id) {
	  		throw new ApiException(
	  				"ApiException::showUsersBlocked user_id field should be positive integer");
	  	}
	  	$this->httpClient->addGet(self::parseRouting(IAPi::URI_USERS_BLOCKED_SHOW,$user_id));
	  	return $this->httpClientSend();	  	
	  }
// 		POST /api/users/{id}/blocked
	  /**
	  * Blocks the given user for the session user
	  *
	  * @param number $user_id
	  * @param number $user_blocked_id
	  */
	  public function addUserBlocked($user_id,$user_blocked_id)
	  {
	  	if (!is_numeric($user_id) || 0 >= $user_id) {
	  		throw new ApiException(
	  				"ApiException::addUserBlocked user_id field should be positive integer");
	  	}
	  	if (!is_numeric($user_blocked_id) || 0 >= $user_blocked_id) {
	  		throw new ApiException(
	  				"ApiException::addUserBlocked user_id field should be positive integer");
	  	}
	  		  	
	  	$this->httpClient->addPost(self::parseRouting(IAPi::URI_USERS_BLOCKED_ADD,$user_id),array('user_id'=>$user_blocked_id));
	  	return $this->httpClientSend();	  	
	  }
//		DELETE /api/users/{user_id}/blocked/{blocked_user_id}
	  /**
	  * Unblocks the given user for the session user
	  *
	  * @param number $user_id
	  * @param number $user_blocked_id
	  */
	  public function delUserBlocked($user_id,$user_blocked_id)
	  {
	  	if (!is_numeric($user_id) || 0 >= $user_id) {
	  		throw new ApiException(
	  				"ApiException::addUserBlocked user_id field should be positive integer");
	  	}
	  	if (!is_numeric($user_blocked_id) || 0 >= $user_blocked_id) {
	  		throw new ApiException(
	  				"ApiException::addUserBlocked user_id field should be positive integer");
	  	}
	  	
	  	$this->httpClient->addDelete(self::parseRouting(IAPi::URI_USERS_BLOCKED_ADD,array('user_id'=>$user_blocked_id)));
	  	return $this->httpClientSend();	  	
	  }
}
