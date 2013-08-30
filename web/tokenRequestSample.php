<?php
require __DIR__.'/../vendor/autoload.php';

use Ant\ChateaClient\OAuth2\TokenRequest;
use Ant\ChateaClient\OAuth2\RefreshToken;
use Ant\ChateaClient\OAuth2\OAuth2Client;
use Ant\ChateaClient\Http\HttpClient;
use Ant\ChateaClient\Http\HttpClientException;



$oauthClient = new OAuth2Client(
		'2_63gig21vk9gc0kowgwwwgkcoo0g84kww00c4400gsc0k8oo4ks',
		'202mykfu3ilckggkwosgkoo8g40w4wws0k0kooo488wo048k0w',
		'http://www.chateagratis.net/'
);
$validAuthCode =  'OTgxMTIwNzIwODc4OGJhMjNmZDEyM2I4NDhiNmQyOTk4MjU3YzdkNjM5NDI5MjE0MzJiMWM2ODYxNWFjNDAzOQ';
$validRefresToken = 'Mjk2M2RiNWEwOGJhYzQ2Y2JmMzI1ODlhNjgxZjQ3YTQ0ZDk3MjRmMjcxMmUxODVlNWMyODkyMzc4OTExZWZhMg';
$validUsername = 'apiuser';
$validpassword = 'apiuser';

$httpClient = new HttpClient(HttpClient::TOKEN_ENDPOINT);
$tokenRequest = new TokenRequest($oauthClient, $httpClient);

try {

	echo "withAuthorizationCode:<br/><br/>";
	$respone = $tokenRequest->withAuthorizationCode($validAuthCode);
	echo $respone;

	echo "<br/><br/>withClientCredentials:<br/><br/>";
	$respone = $tokenRequest->withClientCredentials();
	echo $respone;


	echo "<br/><br/>withUserCredentials:<br/><br/>";
	$respone = $tokenRequest->withUserCredentials($validUsername, $validpassword);
	echo $respone;

	echo "<br/><br/>withRefreshToken:<br/><br/>";
	$respone = $tokenRequest->withRefreshToken(new RefreshToken($validRefresToken));
	echo $respone;

}catch (HttpClientException $e){
		echo $e->getResponseMessage();
}	