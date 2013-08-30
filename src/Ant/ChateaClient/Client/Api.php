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

	/**
	 * List all the channels
	 *
	 * @throws ApiException This exception is run, if error in client
	 *
	 * @return json whith all channles
	 */		
	public function showChannels() 
	{
		$this->httClient->addGetData('',IApi::URI_CHANNELS_SHOW);
		try{
			
			return $this->httClient->send(true);
		}catch (HttpClientException $ex){
			throw new ApiException($ex->getMessage());
		}
	}
	public function addChanel($name, $title = '', $description = '') {
		// TODO: Auto-generated method stub

	}
	public function updateChannel($id) {
		// TODO: Auto-generated method stub

	}
	public function delChannel($id) {
		// TODO: Auto-generated method stub

	}
	public function showChannel($id) {
		// TODO: Auto-generated method stub

	}
	public function showMeFriends() {
		// TODO: Auto-generated method stub

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
		// TODO: Auto-generated method stub

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
