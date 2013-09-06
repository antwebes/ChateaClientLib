<?php
require __DIR__.'/../vendor/autoload.php';

use Ant\ChateaClient\OAuth2\TokenRequest;
use Ant\ChateaClient\OAuth2\RefreshToken;
use Ant\ChateaClient\OAuth2\OAuth2Client;
use Ant\ChateaClient\OAuth2\OAuth2ClientAuthCode;
use Ant\ChateaClient\OAuth2\OAuth2ClientUserCredentials;
use Ant\ChateaClient\OAuth2\OAuth2ClientCredentials ;
use Ant\ChateaClient\Http\HttpClient;
use Ant\ChateaClient\Http\HttpClientException;

use Ant\ChateaClient\Client\AuthClientCredentials;
use Ant\ChateaClient\Client\AuthUserCredentials;
use Ant\ChateaClient\Client\Api;
use Ant\ChateaClient\Client\ApiException;
use Ant\ChateaClient\Client\SessionStorage;
use Ant\ChateaClient\Client\IApi;


$oauthClient = new OAuth2ClientCredentials(
		'2_63gig21vk9gc0kowgwwwgkcoo0g84kww00c4400gsc0k8oo4ks',
		'202mykfu3ilckggkwosgkoo8g40w4wws0k0kooo488wo048k0w'
		);
$validAuthCode =  'OTgxMTIwNzIwODc4OGJhMjNmZDEyM2I4NDhiNmQyOTk4MjU3YzdkNjM5NDI5MjE0MzJiMWM2ODYxNWFjNDAzOQ';
$validRefresToken = 'Mjk2M2RiNWEwOGJhYzQ2Y2JmMzI1ODlhNjgxZjQ3YTQ0ZDk3MjRmMjcxMmUxODVlNWMyODkyMzc4OTExZWZhMg';
$validUsername = 'apiuser';
$validpassword = 'apiuser';


	$httpClientAuth = new HttpClient(HttpClient::TOKEN_ENDPOINT);
	$httpClientApi = new HttpClient(HttpClient::SERVER_ENDPOINT);
	$auth = new AuthClientCredentials($oauthClient,$httpClientAuth);

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

	
	$httpClientApi->addAccesToken($accessToken);
	
	echo "<br>TOKEN<br>";
	echo $accessToken->getValue();
	echo "<br><br>";
	
	$api = new Api($httpClientApi);


//----------------------------------------------------------------------------//		
//----------    CHANNELS      ------------------------------------------------//
//----------------------------------------------------------------------------//
//	  	echo $api->showChannels();
		echo $api->addChanel("Xabier Channels_".time(), "new chanel title");	
// 		echo $api->updateChannel(21, 'new chanel 26');
// 		echo $api->showChannel(21);
// 		echo $api->delChannel(20);	
//		echo $api->showChannel('21');
	
//----------------------------------------------------------------------------//
//----------		Friends	    -----------------------------------------------//
//----------------------------------------------------------------------------//
		
//		echo $api->showMeFriends();	
//     	echo $api->showFriendshipsRequest();
//     	echo $api->showFriendshipsPending();
//		echo $api->delFriendship(9);
//		echo $api->addMeFirend(6);	
//		echo $api->showFriendshipsRequest();

//----------------------------------------------------------------------------//
//----------		PHOTO      -----------------------------------------------//
//----------------------------------------------------------------------------//

// 	echo $api->addPhoto("concierto","/home/ant3/concierto.jpeg");
// 	echo $api->showPhoto(1);
// 	echo $api->delPhoto(1);
	
// 	echo $api->addThread('xabierTest','Subject','<script>alert("holas");</script>');
// 	echo $api->showThreadsInbox();
// 	echo $api->showThreadsSent();
// 	echo $api->addThreadMessage(5,'<script>alert("holas");</script>');
// 	echo $api->showThread(6);	
//	echo $api->delThread(6);


//	echo $api->showMeVotes();
//	echo $api->addVote(9, 10);
//	echo $api->delVote(11);

	
	
//	echo $api->updateProfile('apiuser', 'apiuser@api.com', 'apiuser');
// 	echo $api->showProfile();
// 	echo "<br/>new Pasword<br/>";	
// 	echo $api->changePassword('apiuser','newapiuser','newapiuser');	
// 	echo "<br/>new Pasword2<br/>";
// 	echo $api->changePassword('newapiuser','apiuser','apiuser');


//	echo "<br><br>". $api->enableUser(10). "<br><br>";	
//	echo "<br><br>". $api->disableUser(7). "<br><br>";
//	echo "<br><br>". $api->delUser(7). "<br><br>";	


//   echo "<br><br>". $api->whoami(). "<br><br>";
	
//   echo "<br><br>". $api->showProfile(). "<br><br>";

	
	
//	echo Api::register(new HttpClient(), 'username', 'email@eamil.com', 'new_password', 'new_password');
//	echo Api::requestResetpassword(new HttpClient(), 'xabierAll');
	
}catch (HttpClientException $e){
	echo "<br><br><br>";
	echo "HttpClientException Message: <br/><br/>";
	echo $e->getResponse(true);	
}catch (ApiException $ex){
	echo "API Error Error: <br/><br/>";
	echo $e->getMessage();
}
