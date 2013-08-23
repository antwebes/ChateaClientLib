<?php
require __DIR__.'/../vendor/autoload.php';

use Ant\ChateaClient\Client\ClientConfig;
use Ant\ChateaClient\Client\ChateaOAuth2;
use Ant\ChateaClient\Client\ChateaApi;
use Ant\ChateaClient\Client\ChateaConfig;
use Ant\ChateaClient\Client\SessionStorage;

$clientConfig = ClientConfig::fromJSONFile(__DIR__.'/../app/config/client_secrets.json');
$chateaConfig = ChateaConfig::createDefaultChateaConfig();
//session_unset();


try {
	// autenticamos
	$auth = new ChateaOAuth2($clientConfig, 'xabier','xabier');
	
	$api = new ChateaApi($clientConfig->getClientId(), $auth);
		
	$api->authenticate();
	
  	print ( "<br><br>".$api->getBearerToken() . "<br><br>");
	
  
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
