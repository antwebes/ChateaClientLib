<?php
require __DIR__.'/../vendor/autoload.php';

use Ant\ChateaClient\OAuth2\TokenRequest;
use Ant\ChateaClient\OAuth2\RefreshToken;
use Ant\ChateaClient\OAuth2\OAuth2Client;
use Ant\ChateaClient\OAuth2\OAuth2ClientAuthCode;
use Ant\ChateaClient\Http\HttpClient;
use Ant\ChateaClient\Http\HttpClientException;
use Ant\ChateaClient\OAuth2\OAuth2ClientCredentials ;
use Ant\ChateaClient\Client\AuthClientCredentials;
use Ant\ChateaClient\Client\AuthUserCredentials;
use Ant\ChateaClient\Client\Api;
use Ant\ChateaClient\Client\SessionStorage;
use Ant\ChateaClient\OAuth2\OAuth2ClientUserCredentials;
//session_unset();

$oauthClient = new OAuth2ClientUserCredentials(
		'2_63gig21vk9gc0kowgwwwgkcoo0g84kww00c4400gsc0k8oo4ks',
		'202mykfu3ilckggkwosgkoo8g40w4wws0k0kooo488wo048k0w',
		'apiuser',
		'apiuser'
		);
$validAuthCode =  'OTgxMTIwNzIwODc4OGJhMjNmZDEyM2I4NDhiNmQyOTk4MjU3YzdkNjM5NDI5MjE0MzJiMWM2ODYxNWFjNDAzOQ';
$validRefresToken = 'Mjk2M2RiNWEwOGJhYzQ2Y2JmMzI1ODlhNjgxZjQ3YTQ0ZDk3MjRmMjcxMmUxODVlNWMyODkyMzc4OTExZWZhMg';
$validUsername = 'apiuser';
$validpassword = 'apiuser';


	$httpClientAuth = new HttpClient(HttpClient::TOKEN_ENDPOINT);
	$httpClientApi = new HttpClient(HttpClient::SERVER_ENDPOINT);
	$auth = new AuthUserCredentials($oauthClient,$httpClientAuth);
	try {

	/*
try {
	$store = SessionStorage::getInstance();
	
	$accessToken = $auth->getAccessToken();
	$refreshToken = $auth->getRefreshToken();
	
	if($auth->isAuthenticationExpired()){
		$accessToken = $store->findAccessTokenByClientId($oauthClient);
		if(!$accessToken || $accessToken->hasExpired()){
			//TODO refresToken has expired.
			$refresToken = $store->findRefreshTokenByClientId($oauthClient);
			if(!$refreshToken || $refreshToken->hasExpired()){
				//new session
				$auth = $auth->authenticate();
				$accessToken = $auth->getAccessToken();
				$refreshToken = $auth->getRefreshToken();
			}else {
				// new session with refresToken
				$auth = $auth->updateToken();
				$accessToken = $auth->getAccessToken();
				$refreshToken = $auth->getRefreshToken();				
			}
			//TODO: save in store			
			$store->updateAccessToken($oauthClient, $accessToken);
			$store->updateRefreshToken($oauthClient, $refreshToken);
			
		}//else acessToken in store
	}//else acessToken not espired
	*/
	$auth = $auth->authenticate();
	$accessToken = $auth->getAccessToken();
	$refreshToken = $auth->getRefreshToken();
	$httpClientApi->addAccesToken($accessToken);
	
	echo "TOKEN<br><br>";
	echo $accessToken->getValue();
	echo "<br><br>";
	
	$api = new Api($httpClientApi);
	echo $api->whoami();
    echo $api->showMeFriends();	

//----------------------------------------------------------------------------//		
//----------    CHANNELS      ------------------------------------------------//
//----------------------------------------------------------------------------//
//	echo $api->addChanel("Xabier Channels_".time(), "new chanel title");	

// 	echo "<br><br>". $api->updateChannel(20, 'new chanel only name')."<br><br>";

// 	echo "<br><br>". $api->showChannel(20). "<br><br>";

  	 
// 	echo "<br><br>". $api->delChannel(19). "<br><br>";
	
// 	echo $api->showChannels(); 
  
//	echo "<br><br>". $api->showChannel('5'). "<br><br>";
	
//	echo "<br><br>". $api->getProfile(). "<br><br>";

 	
//----------------------------------------------------------------------------//		
//----------    CHANNELS      ------------------------------------------------//
//----------------------------------------------------------------------------//
	//echo $api->whoami();
	//echo $api->showMeFriends();	
	
	
	
	
	
	
	
	
	
//	echo "<br><br>". $api->changePassword('xabier',"xabier","xabier"). "<br><br>";

//	echo "<br><br>". $api->editProfile("xabier","noimalk@no.com",'xabier'). "<br><br>";	 

// 	echo "<br><br>". $api->getAllUser(). "<br><br>";

//	echo "<br><br>". $api->getWhoami(). "<br><br>";
	
//   echo "<br><br>". $api->getProfile(). "<br><br>";
//   echo "<br><br>". $api->deleteUser(). "<br><br>";
	
//	echo "<br><br>". ChateaApi::register($chateaConfig, 'xabierAll2', 'xabier@none.com', '123456', '123456'). "<br><br>";
	
//	echo "<br><br>". ChateaApi::requestResetpassword($chateaConfig, "xabierAll2");
	
}catch (HttpClientException $e){
	echo "Response Error: <br/><br/>";
	echo $e->getResponseMessage();	
	echo "<br/><br/>Only Error:<br/><br/>";
	echo $e->getErrorMensage();
}
