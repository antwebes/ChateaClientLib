<?php
namespace Ant\ChateaClient\Client;

use Ant\ChateaClient\Http\IHttpClient;
interface IApi
{
	const URI_CHANNELS_SHOW					= 'api/channel/';
	const URI_CHANNEL_ADD	 				= 'api/channel/';
	const URI_CHANNEL_UPDATE				= 'api/channel/';
	const URI_CHANNEL_DEL	 				= 'api/channel/';
	const URI_CHANNEL_SHOW	 				= 'api/channel/';		
	//--------------------------------------------------------------------------
	const URI_ME_FRIENDS_SHOW				= 'api/me/friends';
	const URI_ME_FRIENDSHIPS_REQUEST_SHOW	= 'api/me/friends';
	const URI_ME_FRIENDSHIPS_PENDING		= 'api/me/friends/pending';
	//--------------------------------------------------------------------------
	const URI_PORFILE_SHOW 					= 'api/profile/';
	const URI_PORFILE_UPDATE				= 'api/profile/edit';
	const URI_PORFILE_CHANGE_PASSWORD 		= 'api/profile/change-password';
	//--------------------------------------------------------------------------	
	const URI_USERS_SHOW					= 'api/user/list';
	const URI_USER_SHOW	 					= 'api/user/me';
	const URI_USER_DEL 						= "api/user/";
	//--------------------------------------------------------------------------	
	const URI_REGISTER						= "register";
	const URI_RESETTING_PASSWORD 			= "resetting/send-email";	

	//GET /api/channel/ 
	/**
	 * List all the channels
	 */
	public function showChannels();	
	// 	POST /api/channel/ 
	/**
	 * Create a channel
	 * 
	 * @param string $name
	 * @param string $title
	 * @param string $description
	 */
	public function addChanel($name ,$title = '',$description = '');
	// 	PUT|PATCH /api/channel/{id} 
	/**
	 * Update a channel
	 * 
	 * @param number $id
	 */
	public function updateChannel($id, $name ,$title = '',$description = '');
	// 	DELETE /api/channel/{id}
	/**
	 * Delete a channel
	 * 
	 * @param number $id
	 */
	public function delChannel($id);	
	// 	GET /api/channel/{id} 
	/**
	 * show a channel by id
	 * 
	 * @param number $id
	 */
	public function showChannel($id);	
	// 	GET /api/me/friends
	/**
	 * returns the friends of the loged in user
	 */
	public function showMeFriends();
	// 	POST /api/me/friends 
	/**
	 * sends a friendship request to a given user
	 * @param number $user_id
	 */
	public function showFriendshipsRequest ($user_id);
	// 	GET /api/me/friends/pending
	/**
	 * returns the friendships request the loged in user sended that are pending froor acceptance
	 */
	public function showFriendshipsPending();
	// 	GET /api/me/friends/requests 
	/**
	 * returns the friendship requests sended the loged in user pending to be accepted
	 */
	public function showMeRequestsFriendship();
	// 	PUT /api/me/friends/requests/{id}
	/**
	 * accepts a friendship request
	 * 
	 * @param number $id
	 */
	public function acceptsFriendshipRequest($id);
	// 	DELETE /api/me/friends/requests/{id}
	/**
	 * Decline a friendship request
	 * 
	 * @param number $i
	 */
	public function declineFriendshipRequest($i);
	// 	DELETE /api/me/friends/{id} 
	/**
	 * Deletes a friendship
	 * 
	 * @param number $id
	 */
	public function delFriendship($id = 0);
	
	// 	POST /api/me/threads Creates a thread
	public function addThread($recipient, $subject, $body);
	// 	POST|PUT /api/photo
	/**
	 * create a photo
	 * 
	 * @param string $title
	 * @param byte[] $image
	 */
	public function addPhoto($title,$image);
	// 	DELETE /api/photo/{id} 
	/**
	 * Delete a photo
	 * 
	 * @param number $id
	 */
	public function delPhoto($id);
	// 	PUT|PATCH /api/profile/
	/**
	 * Update a profile of an user
	 */
	public function updateProfile($username, $email, $current_password);
	// 	GET /api/profile/ 
	/**
	 * Show a profile of an user
	 */
	public function showProfile();
	// 	PATCH /api/profile/change-password 
	/**
	 * Change user password
	 * 
	 * @param string $current_password
	 * @param string $new_password
	 * @param String $repeat_new_password
	 */
	public function changePassword($current_password, $new_password, $repeat_new_password);
	// 	DELETE /api/user/
	/**
	 * Delete my user
	 */
	public function delMeUser();
	// 	GET /api/user/list
	/**
	 * Get all the users
	 */
	public function who();	
	// 	GET /api/user/me
	/**
	 * get the user of session
	 */
	public function whoami();
	// 	GET /api/users/{id}/friends
	/**
	 * accepts a friendship request
	 * 
	 * @param number $id
	 */
	public function showFriends($id);
	// 	PATCH  /user/disable/{id} 
	/**
	 * disable an user by id
	 * 
	 * @param number $id
	 */
	public function disableUser($id);
	// 	PATCH  /user/enable/{id} 
	/**
	 * Enable an user by id CONFIGURE ANOTTATION @SECURE because mandatory redirect to login
	 * 
	 * @param number $id
	 */
	public function enableUser($id);
	// 	DELETE  /user/{id}
	/**
	 * delete an user
	 * 
	 * @param number $id
	 */
	public function delUser($id);	
	// 	POST /register
	/**
	 *  create a user
	 *
	 * @param IHttpClient $httpClient
	 * @param string $username
	 * @param string $email
	 * @param string $new_password
	 * @param string $repeat_new_password
	 */
	 public static function register(IHttpClient $httpClient,  $username, $email,$new_password, $repeat_new_password);
	 // 	POST /resetting/send-email
	/**
	* Request reset user password, in the request is mandatory send username or email (dont work from nelmio api)
	 *
	 * @param IHttpClient $httpClient
	 * @param string $username
	 */
	 public static function requestResetpassword(IHttpClient $httpClient, $username);	
}