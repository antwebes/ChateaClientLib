<?php
require __DIR__.'/../vendor/autoload.php';

use Ant\ChateaClient\Http\HttpClient;
use Ant\ChateaClient\OAuth2\OAuth2UserCredentials ;
use Ant\ChateaClient\Client\AuthUserCredentials;
use Ant\ChateaClient\Client\AuthenticationException;

$oauthClient = new OAuth2UserCredentials(
		'2_63gig21vk9gc0kowgwwwgkcoo0g84kww00c4400gsc0k8oo4ks',
		'202mykfu3ilckggkwosgkoo8g40w4wws0k0kooo488wo048k0w',
		'apiuser',
		'apiuser'
		);

$httpClient = new HttpClient(HttpClient::TOKEN_ENDPOINT);
$auth = new AuthUserCredentials($oauthClient,$httpClient);
try {

	$auth->authenticate();
	echo "1. Authenticate:<br/><br/>";
	echo "----- AccesToken: <br/><br/>";
	echo  $auth->getAccessToken()->getValue();
	echo "<br/><br/>----- RefreshToken:<br/><br/>";
	echo  $auth->getRefreshToken()->getValue();

	
	echo "<br/><br/>2. Update with refrestoken: <br/><br/>";
	$auth->updateToken();

	echo "----- AccesToken: <br/><br/>";
	echo  $auth->getAccessToken()->getValue();
	echo "<br/><br/>----- RefreshToken: <br/><br/>";
	echo  $auth->getRefreshToken()->getValue();

}catch (AuthenticationException $e){
		echo $e->getResponseMessage();
}
	