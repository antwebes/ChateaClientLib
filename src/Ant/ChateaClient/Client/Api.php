<?php
namespace Ant\ChateaClient\Client;

use Ant\ChateaClient\Http\IHttpClient;
use Ant\ChateaClient\Http\HttpClient;
class Api implements IApi 
{
	
	private $httClient;
	
	public function __construct(IHttpClient $httpClient) 
	{
		if(null === $httpClient){
			$client = new HttpClient(IHttpClient::SERVER_ENDPOINT);
		}
		$this->httClient = $httpClient;
	}

	private function httpClientSend()
	{
		try{
			return $this->httClient->send(true);
		}catch (HttpClientException $ex){
			throw new ApiException($ex->getMessage());
		}		
	}
	/**
	 * List all the channels
	 *
	 * @throws ApiException This exception is run, if error in client
	 *
	 * @return json whith all channles
	 */		
	public function showChannels() 
	{
		$this->httClient->addGet(IApi::URI_CHANNELS_SHOW);
		return $this->httpClientSend();
	}
	/**
	 * Create a channel
	 * 
	 * @param string $name
	 * @param string $title
	 * @param string $description
	 * 
	 */
	public function addChanel($name, $title = '', $description = '') 
	{
	     if (!is_string($name) || 0 >= strlen($name)) {
            throw new ApiException("ApiException::addChanel name field needs to be a non-empty string");
        }
		
		$data = array(
				'channel'=>array(
						'name'=>$name
				)
		);
		if(!empty($title)){
			$data['channel']['title'] = $title;
		}
		if(!empty($title)){
			$data['channel']['description'] = $description;
		}	
                
        $this->httClient->addPost(IApi::URI_CHANNEL_ADD,$data);
        return $this->httpClientSend();

	}
	public function updateChannel($id, $name ,$title = '',$description = '') 
	{
		if (!is_numeric($id) || 0 >= $id) {
			throw new TokenException("ApiException::updateChannel id field should be positive integer");
		}		
		if (!is_string($name) || 0 >= strlen($name)) {
			throw new ApiException("ApiException::updateChannel name field needs to be a non-empty string");
		}
				
		$data = array(
				'channel'=>array(
						'name'=>$name
				)
		);
		if(!empty($title)){
			$data['channel']['title'] = $title;
		}
		if(!empty($title)){
			$data['channel']['description'] = $description;
		}		
		$this->httClient->addPatch(IApi::URI_CHANNEL_UPDATE.$id, $data);
		return $this->httpClientSend();
	}
	public function delChannel($id) 
	{
		if (!is_numeric($id) || 0 >= $id) {
			throw new TokenException("ApiException::updateChannel id field should be positive integer");
		}		
		$this->httClient->addDelete(IApi::URI_CHANNEL_DEL.$id);
		return $this->httpClientSend();		
	}
	public function showChannel($id) 
	{
		if (!is_numeric($id) || 0 >= $id) {
			throw new TokenException("ApiException::showChannel id field should be positive integer");
		}
		$this->httClient->addGet(IApi::URI_CHANNEL_SHOW.$id);
		return $this->httpClientSend();
	}
	public function showMeFriends() 
	{
		$this->httClient->addGet(IApi::URI_ME_FRIENDS_SHOW);
		return $this->httpClientSend();

	}
	public function showFriendshipsRequest($user_id) {
		// TODO: Auto-generated method stub

	}
	public function showFriendshipsPending() {
		// TODO: Auto-generated method stub

	}
	public function showMeRequestsFriendship() {
		// TODO: Auto-generated method stub

	}
	public function acceptsFriendshipRequest($id) {
		// TODO: Auto-generated method stub

	}
	public function declineFriendshipRequest($i) {
		// TODO: Auto-generated method stub

	}
	public function delFriendship($id = 0) {
		// TODO: Auto-generated method stub

	}
	public function addThread($recipient, $subject, $body) {
		// TODO: Auto-generated method stub

	}
	public function addPhoto($title, $image) {
		// TODO: Auto-generated method stub

	}
	public function delPhoto($id) {
		// TODO: Auto-generated method stub

	}
	public function updateProfile($username, $email, $current_password) {
		// TODO: Auto-generated method stub

	}
	public function showProfile() {
		// TODO: Auto-generated method stub

	}
	public function changePassword($current_password, $new_password,
			$repeat_new_password) {
		// TODO: Auto-generated method stub

	}
	public function delMeUser() {
		// TODO: Auto-generated method stub

	}
	public function who() {
		// TODO: Auto-generated method stub

	}
	public function whoami() {
		$this->httClient->addGet(IApi::URI_USER_SHOW);
		return $this->httpClientSend();

	}
	public function showFriends($id) {
		// TODO: Auto-generated method stub

	}
	public function disableUser($id) {
		// TODO: Auto-generated method stub

	}
	public function enableUser($id) {
		// TODO: Auto-generated method stub

	}
	public function delUser($id) {
		// TODO: Auto-generated method stub

	}
	public static function register(IHttpClient $httpClient, $username, $email,
			$new_password, $repeat_new_password) {
		// TODO: Auto-generated method stub

	}
	public static function requestResetpassword(IHttpClient $httpClient, $username) {
		// TODO: Auto-generated method stub

	}

}
