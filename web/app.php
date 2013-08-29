<?php
use Ant\ChateaClient\OAuth2\TokenRequest;
use Ant\ChateaClient\OAuth2\OAuth2Client;
use Ant\ChateaClient\OAuth2\OAuth2ClientAuthCode;
use Ant\ChateaClient\Http\HttpClient;
require __DIR__.'/../vendor/autoload.php';
//session_unset();

$oauthClient = new OAuth2ClientAuthCode(
		'2_63gig21vk9gc0kowgwwwgkcoo0g84kww00c4400gsc0k8oo4ks',
		'202mykfu3ilckggkwosgkoo8g40w4wws0k0kooo488wo048k0w',
		'OTgxMTIwNzIwODc4OGJhMjNmZDEyM2I4NDhiNmQyOTk4MjU3YzdkNjM5NDI5MjE0MzJiMWM2ODYxNWFjNDAzOQ',
		'http://www.chateagratis.net/'
		);
$httpClient = new HttpClient();
try {
	$tokenRquest = new TokenRequest($oauthClient, $httpClient)
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
	
  echo "<br><br>". $api->getProfile(). "<br><br>";
 	echo "<br><br>". $api->deleteUser(). "<br><br>";

	
//	echo "<br><br>". ChateaApi::register($chateaConfig, 'xabierAll2', 'xabier@none.com', '123456', '123456'). "<br><br>";
	
//	echo "<br><br>". ChateaApi::requestResetpassword($chateaConfig, "xabierAll2");
	
}catch (\Exception $e){
	echo $e->getMessage();
}
