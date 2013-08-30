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
//session_unset();

$oauthClient = new OAuth2ClientCredentials(
		'2_63gig21vk9gc0kowgwwwgkcoo0g84kww00c4400gsc0k8oo4ks',
		'202mykfu3ilckggkwosgkoo8g40w4wws0k0kooo488wo048k0w',
		'http://www.chateagratis.net'
		);
$validAuthCode =  'OTgxMTIwNzIwODc4OGJhMjNmZDEyM2I4NDhiNmQyOTk4MjU3YzdkNjM5NDI5MjE0MzJiMWM2ODYxNWFjNDAzOQ';
$validRefresToken = 'Mjk2M2RiNWEwOGJhYzQ2Y2JmMzI1ODlhNjgxZjQ3YTQ0ZDk3MjRmMjcxMmUxODVlNWMyODkyMzc4OTExZWZhMg';
$validUsername = 'apiuser';
$validpassword = 'apiuser';

	$httpClient = new HttpClient(HttpClient::TOKEN_ENDPOINT);
	$auth = new AuthClientCredentials($oauthClient,$httpClient);
try {

	$auth->authenticate();
	
	echo  $auth->getAccessToken()->getValue();
	echo "<br/><br/>";
	echo  $auth->getRefreshToken()->getValue();
	// autenticamos
//	$auth = new ChateaOAuth2($clientConfig, 'xabier','xabier');
	
//	$api = new ChateaApi($clientConfig->getClientId(), $auth);
		
//	$api->authenticate();
	
//  	print ( "<br><br>".$api->getBearerToken() . "<br><br>");
	
  
// echo "<br><br>".$api->getAllChannels() ."<br><br>" ;
	
	
//	echo "<br><br>". $api->createChannel("My fantastic channel".time(), "My fantasatic tile".time(), "My fantasatic descripcion".time())."<br><br>";
 
//  	echo "<br><br>". $api->updateChannel(12, 'new chanel name',"title name", "title dess")."<br><br>";
    
//	echo "<br><br>". $api->deleteChannel(12). "<br><br>";
	
  
//	echo "<br><br>". $api->getChannel('5'). "<br><br>";
	
//	echo "<br><br>". $api->getProfile(). "<br><br>";

 	
//	echo "<br><br>". $api->changePassword('xabier',"xabier","xabier"). "<br><br>";

//	echo "<br><br>". $api->editProfile("xabier","noimalk@no.com",'xabier'). "<br><br>";	 

// 	echo "<br><br>". $api->getAllUser(). "<br><br>";

//	echo "<br><br>". $api->getWhoami(). "<br><br>";
	
//   echo "<br><br>". $api->getProfile(). "<br><br>";
//   echo "<br><br>". $api->deleteUser(). "<br><br>";
	
//	echo "<br><br>". ChateaApi::register($chateaConfig, 'xabierAll2', 'xabier@none.com', '123456', '123456'). "<br><br>";
	
//	echo "<br><br>". ChateaApi::requestResetpassword($chateaConfig, "xabierAll2");
	
}catch (HttpClientException $e){
	echo $e->getResponseMessage();	
}
