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
class Api implements IApi {

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
	 	
	 	$this->httClient->addPost(IApi::URI_CHANNEL_ADD, $data);
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
	 	
	 	$this->httClient->addPatch( str_replace('{id}',$channel_id, IApi::URI_CHANNEL_UPDATE),$data);
	 	
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
	 	$this->httClient->addPatch( str_replace('{id}',$channel_id, IApi::URI_CHANNEL_UPDATE));
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
	 	$this->httClient->addGet(IApi::URI_CHANNEL_SHOW . $channel_id);
	 	return $this->httpClientSend();	 	
	 }
	 //	GET /api/users/{id}/channels
	 /**
	 *  Show list channels create of an user
	 */
	 public function showChannelsByUser()
	 {
	 	
	 }
	 // 	GET /api/channels/{id}/fans
	 /**
	 * Show all fans of a channel
	 *
	 * @param number $channel_id
	 */
	 public function showChannelFans($channel_id)
	 {
	 	
	 }
	 //	GET /api/users/{id}/channelsFan
	 /**
	 * Show all channels fan of an user
	 *
	 * @param number $user_id
	 */
	 public function showChannelsFan($user_id)
	 {
	 	
	 }
	 //	POST /api/users/{user_id}/channels/{channel_id}/fans
	 /**
	 * Make user a channel fan
	 *
	 * @param number $user_id
	 */
	 public function addChannelFan($channel_id, $user_id = null)
	 {
	 	
	 }
	 //	DELETE /api/users/{user_id}/channels/{channel_id}/fans
	 /**
	 * Remove user as a channel fan.
	 *
	 * @param number $channel_id
	 * @param number $user_id
	 */
	 public function delChannelFan($channel_id, $user_id = null)
	 {
	 	
	 }
	 
}
