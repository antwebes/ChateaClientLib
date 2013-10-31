<?php
require __DIR__.'/../vendor/autoload.php';

use Ant\ChateaClient\Service\Client\ChateaOAuth2Client;
use Ant\ChateaClient\Client\Authentication;
use Guzzle\Http\Exception\BadResponseException;

$client = ChateaOAuth2Client::factory(array('environment'=>'dev','client_id'=>'2_random-demo','secret'=>'secret-demo'));
//config app data
$client_id ='2_random-demo';
$secret = 'secret-demo';

//users data
$username = 'alex';
$password = 'alex';
$authCode = 'auth-token-test';
$rediret = 'http://www.chateagratis.net';

try{
    // withUserCredentials
    $auth = $client->withUserCredentials($username,$password);
    print_r ( $auth );
    echo "<br><br><br>";

    print_r(  $client->revokeToken($auth['access_token']));
    echo "<br><br><br>";

    // withAuthorizationCode
    print_r( $client->withAuthorizationCode($authCode,$rediret));
    echo "<br><br><br>";



    //withClientCredentials
    print_r(  $client->withClientCredentials() );
    $model = $client->withClientCredentials();
    echo "<br><br><br>";

    //withRefreshToken
    print_r(  $client->withRefreshToken($model['refresh_token']) );
    echo "<br><br><br>";


}catch (Exception $ex){
    echo "<br>ERROR<br>";
    echo $ex->getMessage();
    ldd($ex);
}
