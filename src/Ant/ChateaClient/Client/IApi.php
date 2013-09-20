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

	
/* 				    				ME		 		  						  */
	const URI_ME_SHOW							= 'api/me';
	const URI_ME_DEL							= 'api/me';	

/* 				    				PHOTO	 		  						  */
	const URI_PHOTO_SHOW							= 'api/photo/{id}';
	const URI_PHOTO_VOTES_SHOW						= 'api/photo/{id}/votes';
	const URI_USER_PHOTO_SHOW						= 'api/user/{id}/photos';
	const URI_USER_PHOTO_ADD						= 'api/users/{id}/photo';
	const URI_USER_PHOTO_VOTES_SHOW					= 'api/users/{id}/vote';
	const URI_USER_PHOTO_VOTES_ADD					= 'api/users/{id}/vote';	
	const URI_USER_PHOTO_VOTES_DEL					= 'api/users/{id}/vote/{photo_id}';
	const URI_USER_PHOTO_DEL						= 'api/users/{user_id}/photo/{photo_id}';
	
/* 				    				THREAD	 		  						  */
		
	const URI_THREAD_ADD						= 'api/users/{id}/threads';
	const URI_THREAD_INBOX_SHOW				    = 'api/users/{id}/threads/inbox';
	const URI_THREAD_SENT_SHOW				    = 'api/users/{id}/threads/sent';
	const URI_THREAD_SHOW					    = 'api/threads/{thread_id}';
	const URI_THREAD_MESSAGE_ADD				= 'api/users/{id}/threads/{thread_id}';
	const URI_THREAD_DEL						= 'api/threads/{thread_id}';


/* 				    				USER	 		  						  */	
	
	const URI_USERS_SHOW						= 'api/users';
	const URI_USER_SHOW							= 'api/users/{$id}';
	const URI_USERS_BLOCKED_SHOW				= 'api/users/{id}/blocked';
	const URI_USERS_BLOCKED_ADD					= 'api/users/{id}/blocked';		
	const URI_USERS_BLOCKED_DEL					= 'api/users/{user_id}/blocked/{blocked_user_id}';

/******************************************************************************/
/******************************************************************************/
			
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
	 * @param number $friend_id
	 */
	 public function addFirend($user_id,$friend_id);
// 	 GET api/users/{id}/friends/pending
	 /**
	 * returns the friends that they are pending accept by an user
	 */
	 public function showFriendshipsPending($user_id);
// 	 GET api/users/{id}/friends/requests
	 /**
	 * returns the requests friendships that one user doesn't have accepted
	 *
	 * @param number $user_id
	 */
	 public function showFriendshipsRequest ($user_id);
// 	 PUT /api/users/{id}/friends/requests/{user_accept_id}
	 /**
	 * accepts a friendship request
	 *
	 * @param number $user_id
	 * @param number $user_accept_id
	 */
	 public function addFriendshipRequest($user_id,$user_accept_id);
// 	 DELETE /api/users/{id}/friends/requests/{user_decline_id}
	 /**
	 * Decline a friendship request
	 *
	 * @param number $user_id
	 * @param number $user_decline_id
	 */
	 public function delFriendshipRequest($user_id,$user_decline_id);
// 	  DELETE api/users/{id}/friends/{user_delete_id}
	 /**
	 * Deletes a friendship
	 *
	 * @param number $user_id
	 * @param number $user_delete_id
	 */
	 public function delFriend($user_id, $user_delete_id);

/******************************************************************************/
/*			  				ME METHODS	    	   					  		  */
/******************************************************************************/	 
// 	 GET /api/me
	 /**
	  * Get my user of session
	  */
	  public function whoami();
	  
//	  DELETE /api/me/
	 /**
	  * Delete my user
	  */
	  public function delMe();

	  
/******************************************************************************/
/*			  				PHOTO METHODS    	   					  		  */
/******************************************************************************/	  
	  
//	GET /api/photo/{id}
	  /**
	  * Show a photo
	  *  
	  * @param number $photo_id
	  */
	  public function showPhoto($photo_id);	  
// 	  GET /api/photo/{id}/votes
	  /**
	  * Show a vote of a photo
	  *  
	  * @param number $photo_id
	  */
	  public function showPhotoVotes($photo_id);		
// 	  GET /api/user/{id}/photos
	  /**
	   * List all photos of an user
	   * 
	   * @param number $user_id
	   */
	  public function showPhotos($user_id);	
// 	  POST /api/users/{id}/photo
	  /**
	   * Create a photo
	   *  
	   * @param number $user_id
       * @param string $imageTile 
	   * @param string $imageFile
	   */
	  public function addPhoto($user_id, $imageTile, $imageFile);	  
// 	  GET /api/users/{id}/vote
	  /**
	   * Show all votes of an user
	   * 
	   * @param number $user_id
	   */
	  public function showUserVotes($user_id);	  
// 	  POST /api/users/{id}/vote

	  /**
	   * Create a vote
	   *  
	   * @param number $user_id
	   * @param number $photo_id
	   * @param number $core
	   */
	  public function addPhotoVote($user_id,$photo_id,$core);	  
// 	  DELETE /api/users/{id}/vote/{photo_id}
	  /**
	   * Delete a vote
	   * @param number $user_id
	   * @param number $photo_id
	   */
      public function delPhotoVote($user_id, $photo_id);
// 	  DELETE /api/users/{user_id}/photo/{photo_id}      
      /**
       * Delete a photo
       * @param number $user_id
       * @param number $photo_id
       */      
      public function delPhoto($user_id,$photo_id);		

      
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
		public function addThread($user_id, $recipient, $subject, $body);
//       GET /api/users/{id}/threads/inbox
//       
		/**
		 * Lists threads with messages had been sent by one user
		 * 
		 * @param number $user_id
		 */
		public function showThreadsInbox($user_id);			
//       GET /api/users/{id}/threads/sent
		/**
		 * Messages list in inbox one user. 
		 * 
		 * @param number $user_id
		 */
		public function showThreadsSent($user_id);
//       GET /api/threads/{thread_id}		
		/**
		 * The messages list a given thread 
		 *  
		 * @param number $thread_id
		 */
		public function showThread($thread_id);		
//       POST /api/users/{id}/threads/{thread_id}		 		
		/**
		 * Replies a message to a given thread
		 *
		 * @param number $user_id
		 * @param number $thread_id
		 * @param string $body
		 */
		 public function addThreadMessage($user_id,$thread_id,$body);
//		DELETE /api/threads/{thread_id}
		/**
		 * Deletes a thread
		 *
		 * @param number thread_id
		 */
		 public function delThread($thread_id);

		 
/******************************************************************************/
/*			  				USER METHODS    	   					  		  */
/******************************************************************************/		 
// 		GET /api/users
		/**
		 * Get all the users
		 */
		 public function who();
// 		GET /api/users/{id}
		/**
		 * Get the user
		 * @param number $user_id
		 */
		public function showUser($user_id);
// 		GET /api/users/{id}/blocked		
		/**
		 * Get blocked users of the session user
		 * @param number $user_id
		 */
		public function showUsersBlocked($user_id);
// 		POST /api/users/{id}/blocked
		/**
		 * Blocks the given user for the session user
		 * 
		 * @param number $user_id
		 * @param number $user_blocked_id
		 */
		public function addUserBlocked($user_id,$user_blocked_id);				
//		DELETE /api/users/{user_id}/blocked/{blocked_user_id}
		/**
		 * Unblocks the given user for the session user
		 *
		 * @param number $user_id
		 * @param number $user_blocked_id
		 */		
		public function delUserBlocked($user_id,$user_blocked_id);
		 		
}
