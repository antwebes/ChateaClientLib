<?php

namespace Ant\ChateaClient\Client;

use Ant\ChateaClient\Http\IHttpClient;

interface IApi 
{

	const URI_REGISTER							= "register";
	const URI_RESETTING_PASSWORD 				= "resetting/send-email";
	
/* 				    				PROFILE 		  						  */

	const URI_PORFILE_SHOW 						= 'api/profile/';
	const URI_PORFILE_UPDATE					= 'api/profile/';
	const URI_PORFILE_CHANGE_PASSWORD 			= 'api/profile/change-password';	

	
/* 				    				CHANNELS 		  						  */
	

	const URI_CHANNELS_SHOW 				= 'api/channels';
	const URI_CHANNEL_ADD 					= 'api/channels';
	const URI_CHANNEL_UPDATE 				= 'api/channels/{id}';
	const URI_CHANNEL_DEL 					= 'api/channels/{id}';
	const URI_CHANNEL_SHOW 					= 'api/channels/{id}';													
	const URI_CHANNEL_FANS_SHOW		  		= 'api/channels/{id}/fans';										
	const URI_USER_CHANNELS_SHOW	 		= 'api/users/{id}/channels';	
	const URI_USER_CHANNEL_FAN_SHOW			= 'api/users/{id}/channelsFan';
	const URI_USER_CHANNEL_FAN_ADD			= 'api/users/{user_id}/channels/{channel_id}/fans';
	const URI_USER_CHANNEL_FAN_DEL			= 'api/users/{user_id}/channels/{channel_id}/fans';

/* 				    				FRIENDSHIP 		  						  */
	const URI_USER_FRIENDS_SHOW					= 'api/users/{id}/friends';
	const URI_USER_FRIEND_ADD					= 'api/users/{id}/friends';
	const URI_USER_FRIENDSHIPS_PENDING_SHOW   	= 'api/users/{id}/friends/pending';
	const URI_USER_FRIENDSHIPS_REQUEST_SHOW		= 'api/users/{id}/friends/requests';
	const URI_USER_FRIENDSHIPS_ACCEPTS			= 'api/users/{id}/friends/requests/{user_accept_id}';
	const URI_USER_FRIENDSHIPS_DECLINE			= 'api/users/{id}/friends/requests/{user_decline_id}';
	const URI_USER_FRIENDSHIPS_DEL				= 'api/users/{id}/friends/{user_delete_id}';

	
		
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
	 	

/******************************************************************************/
/*				  				  PROFILE METHODS    	   					  */
/******************************************************************************/
 //  GET /api/profile/
	 /**
	  * Show a profile of an user
	 */
	 public function showProfile();
// 	 PUT|PATCH /api/profile/
	 /**
	  * Update a profile of an user
	  */
	 public function updateProfile($username, $email, $current_password);
// 	 PATCH /api/profile/change-password
	 /**
	  * Change user password
	  *
	  * @param string $current_password
	  * @param string $new_password
	  * @param String $repeat_new_password
	  */
	 public function changePassword($current_password, $new_password, $repeat_new_password);
	  
	  	 	 
/******************************************************************************/
/*				  				  CHANNEL METHODS    	   					  */
/******************************************************************************/
	 	 
//	GET /api/channels/
	/**
	 * List all the channels
	 */
	public function showChannels();	
// 	POST /api/channels/
	/**
	 * Create a channel
	 *
	 * @param string $name
	 * @param string $title
	 * @param string $description
	 */
	public function addChanel($name ,$title = '',$description = '');
// 	PATCH|PUT /api/channels/{id}
	/**
	 * Update a channel
	 *
	 * @param number $channel_id
	 * @param string $name
	 * @param string $title
	 * @param string $description
	 */
	public function updateChannel($channel_id, $name, $title = '', $description = '');
// 	DELETE /api/channels/{id};
	/**
	 * Delete a channel
	 *
	 * @param number $channel_id
	 */
	public function delChannel($channel_id);
// 	GET /api/channels/{id}
	/**
	 * Show a channel by id
	 *
	 * @param number $id
	 */
	public function showChannel($channel_id);
	// 	GET /api/channels/{id}/fans
	/**
	 * Show all fans of a channel
	 *
	 * @param number $channel_id
	 */
	 public function showChannelFans($channel_id);			
//	GET /api/users/{id}/channels
	/**
	 * Show list channels create of an user
	 * 
	 * @param number $user_id
	 */
	public function showUserChannels($user_id); 		
//	GET /api/users/{id}/channelsFan
	/**
	 * Show all channels fan of an user
	 *  
	 * @param number $user_id
	 */
	public function showUserChannelsFan($user_id);    
//	POST /api/users/{user_id}/channels/{channel_id}/fans
	/**
	 * Make user a channel fan
	 *
	 * @param number $user_id
	 */
	public function addUserChannelFan($channel_id, $user_id);
//	DELETE /api/users/{user_id}/channels/{channel_id}/fans
	/**
	 * Remove user as a channel fan.
	 *
	 * @param number $channel_id
	 * @param number $user_id
	 */
	public function delUserChannelFan($channel_id, $user_id);

/******************************************************************************/
/*			  			FRIENDSHIP METHODS    	   					  		  */
/******************************************************************************/	
	
// 	GET api/users/{id}/friends
	/**
	 * returns the friends of user
	 */
	 public function showFriends($user_id);
//	 POST api/users/{id}/friends
	 /**
	 * sends a friendship request to a given user
	 *
	 * @param number $user_id
	 */
	 public function addFirend($user_id);
// 	 GET api/users/{id}/friends/pending
	 /**
	 * returns the friends that they are pending accept by an user
	 */
	 public function showFriendshipsPending($user_id);
// 	 GET /api/users/{id}/friends/requests
	 /**
	 * returns the requests friendships that one user doesn't have accepted
	 *
	 * @param number $user_id
	 */
	 public function showFriendshipsRequest ($user_id);
	 // 	PUT /api/me/friends/requests/{id}
	 /**
	 * accepts a friendship request
	 *
	 * @param number $id
	 */
	 public function addFriendshipRequest($id);
	 // 	DELETE /api/me/friends/requests/{id}
	 /**
	 * Decline a friendship request
	 *
	 * @param number $i
	 */
	 public function delFriendshipRequest($id);
	 // 	DELETE /api/me/friends/{id}
	 /**
	 * Deletes a friendship
	 *
	 * @param number $id
	 */
	 public function delFriend($user_id = 0);	
}
