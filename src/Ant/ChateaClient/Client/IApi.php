<?php

namespace Ant\ChateaClient\Client;

use Ant\ChateaClient\Http\IHttpClient;

interface IApi 
{

/******************************************************************************/	
/****************************    CHANNELS    **********************************/
/******************************************************************************/	
	const URI_CHANNELS_SHOW 				= 'api/channels';
	const URI_CHANNEL_ADD 					= 'api/channels';
	const URI_CHANNEL_UPDATE 				= 'api/channels/{id}';
	const URI_CHANNEL_DEL 					= 'api/channels/{id}';
	const URI_CHANNEL_SHOW 					= 'api/channels/{id}';
	const URI_CHANNELS_BY_USER_SHOW 		= 'api/users/{id}/channels';
	const URI_CHANNEL_FANS_SHOW		  		= 'api/channels/{id}/fans';
	const URI_CHANNELS_FANS_BY_USER_SHOW	= 'api/users/{id}/channelsFan';
	const URI_CHANNEL_FAN_MAKE_ADD			= 'api/users/{user_id}/channels/{channel_id}/fans';
	const URI_CHANNEL_FAN_MAKE_DEL			= 'api/users/{user_id}/channels/{channel_id}/fans';
	
	
	
	
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
//	GET /api/users/{id}/channels
	/**
	 *  Show list channels create of an user
	 */
	public function showChannelsByUser(); 		
// 	GET /api/channels/{id}/fans
	/**
	 * Show all fans of a channel
	 *
	 * @param number $channel_id
	 */
	public function showChannelFans($channel_id);
//	GET /api/users/{id}/channelsFan
	/**
	 * Show all channels fan of an user
	 *  
	 * @param number $user_id
	 */
	public function showChannelsFan($user_id);    
//	POST /api/users/{user_id}/channels/{channel_id}/fans
	/**
	 * Make user a channel fan
	 *
	 * @param number $user_id
	 */
	public function addChannelFan($channel_id, $user_id = null);
//	DELETE /api/users/{user_id}/channels/{channel_id}/fans
	/**
	 * Remove user as a channel fan.
	 *
	 * @param number $channel_id
	 * @param number $user_id
	 */
	public function delChannelFan($channel_id, $user_id = null);
	
}