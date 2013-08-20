<?php
require __DIR__.'/../vendor/autoload.php';

use Ant\ChateaClient\Client\ClientConfig;
use Ant\ChateaClient\Client\ChateaOAuth2;
use Ant\ChateaClient\Client\ChateaApi;
use Ant\ChateaClient\Client\ChateaConfig;

$clientConfig = ClientConfig::fromJSONFile(__DIR__.'/../app/config/client_secrets.json');
$chateaConfig = ChateaConfig::createDefaultChateaConfig();
session_unset();


try {
	//$api = new ChateaApi($clientConfig, 'xabier','newPasword');
	
	// print ( "<br><br>".$api->getBearerToken() . "<br><br>");
	
	//echo "<br><br>".$api->getAllChannels() ."<br><br>" ;
	
	
	//echo "<br><br>". $api->createChannel("My fantastic channel".time(), "My fantasatic tile2", "My fantasatic descripcion2")."<br><br>";
	
	//echo "<br><br>". $api->deleteChannel('9'). "<br><br>";
	
	//echo "<br><br>". $api->getChannel('9'). "<br><br>";
	
//	echo "<br><br>". $api->getProfile(). "<br><br>";

//	echo "<br><br>". $api->editProfile("xabier","noimalk@no.com",'xabier'). "<br><br>";
 
 	
//	echo "<br><br>". $api->changePassword('xabier',"newPasword","newPasword"). "<br><br>";

// 	echo "<br><br>". $api->getAllUser(). "<br><br>";

//	echo "<br><br>". $api->getUserMe(). "<br><br>";
	
	
// 	echo "<br><br>". $api->deleteUser(1). "<br><br>";

	
	echo "<br><br>". ChateaApi::register($chateaConfig, 'xabierNon', 'xabier@xupiter.org', '123456', '123456'). "<br><br>";
	
}catch (\Exception $e){
	echo $e->getMessage();
}
