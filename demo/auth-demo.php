<?php
require __DIR__.'/../vendor/autoload.php';

use Ant\ChateaClient\Service\Client\ChateaOAuth2Client;
use Ant\ChateaClient\Client\Authentication;
use Guzzle\Http\Exception\BadResponseException;

$client = ChateaOAuth2Client::factory(array('environment'=>'dev'));
//config app data
$client_id ='1_client_demo';
$secret = 'secret-demo';

$authentication = new Authentication($client,$client_id,$secret);

//users data
$username = 'alex';
$password = 'alex';
$authCode = 'auth-token-test';
$rediret = 'http://www.chateagratis.net';

try{
    // withUserCredentials
    print_r ( $authentication->withUserCredentials($username,$password) );
    echo "<br><br><br>";

    // withAuthorizationCode
    print_r( $authentication->withAuthorizationCode($authCode,$rediret));
    echo "<br><br><br>";


    //withClientCredentials
    print_r(  $authentication->withClientCredentials() );
    $model = $authentication->withClientCredentials();
    echo "<br><br><br>";

    //withRefreshToken
    print_r(  $authentication->withRefreshToken($model['refresh_token']) );
    echo "<br><br><br>";

}catch (Exception $ex){
    echo "<br>ERROR<br>";
    echo $ex->getMessage();
    ldd($ex);
}
